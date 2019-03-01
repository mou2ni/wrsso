<?php

namespace App\Controller;

use App\Entity\Comptes;
use App\Entity\DepotRetraits;
use App\Entity\JourneeCaisses;
use App\Form\DecaissementType;
use App\Form\DepotType;
use App\Form\EncaissementType;
use App\Form\RetraitType;
use App\Repository\DepotRetraitsRepository;
use App\Utils\GenererCompta;
use App\Utils\SessionUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/depotRetraits")
 */
class DepotRetraitsController extends Controller
{
    private $utilisateur;
    private $caisse;
    private $journeeCaisse;

    public function __construct(SessionUtilisateur $sessionUtilisateur)
    {
        $this->utilisateur = $sessionUtilisateur->getUtilisateur();
        //dernière caisse ouverte par l'utilisateur ou null si inexistant
        $this->caisse = $sessionUtilisateur->getLastCaisse();
        //dernière journée de la caisse ou null si inexistant
        $this->journeeCaisse = $sessionUtilisateur->getJourneeCaisse();
    }
    /**
     * @Route("/", name="depot_retraits_index", methods="GET")
     */
    public function index(DepotRetraitsRepository $depotRetraitsRepository): Response
    {
        return $this->render('depot_retraits/index.html.twig', ['depot_retraits' => $depotRetraitsRepository->findAll()]);
    }

    /**
     * @Route("/{id}/imprimer", name="depot_retraits_imprimer", methods="GET|POST")
     */
    public function imprimer(Request $request,DepotRetraits $depotRetrait): Response
    {
        return $this->render('depot_retraits/recu_depot_retrait.html.twig',['depotRetrait'=>$depotRetrait,'solde'=>null, 'journeeCaisse'=>$this->journeeCaisse,'typeOperation'=>null]);

    }

    /**
     * @Route("/depot", name="depot_retraits_depot", methods="GET|POST")
     * @Security("has_role('ROLE_GUICHETIER')")
     */
    public function deposer(Request $request,\Swift_Mailer $mailer): Response
    {
        return $this->depotRetrait($request,$mailer, 'depot');

    }

    /**
     * @Route("/retrait", name="depot_retraits_retrait", methods="GET|POST")
     * @Security("has_role('ROLE_GUICHETIER')")
     */
    public function retirer(Request $request,\Swift_Mailer $mailer): Response
    {
        return $this->depotRetrait($request,$mailer,'retrait');
    }

    /**
     * @Route("/encaissement", name="depot_retraits_encaissement", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function encaisser(Request $request,\Swift_Mailer $mailer): Response
    {
        return $this->depotRetrait($request,$mailer, 'encaissement');

    }

    /**
     * @Route("/decaissement", name="depot_retraits_decaissement", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function decaisser(Request $request,\Swift_Mailer $mailer): Response
    {
        return $this->depotRetrait($request,$mailer,'decaissement');
    }

    private function depotRetrait(Request $request,\Swift_Mailer $mailer, $typeOperation='depot'){

        $depotRetrait = new DepotRetraits();
        $depotRetrait->setDateOperation(new \DateTime());

        //choisir le bon type
        switch ($typeOperation){
            case 'depot':
                $form = $this->createForm(DepotType::class, $depotRetrait);
                $returnTwig='depot.html.twig';
                $route='depot_retraits_depot';
                break;
            case 'retrait':
                $form = $this->createForm(RetraitType::class, $depotRetrait);
                $returnTwig='retrait.html.twig';
                $route='depot_retraits_retrait';
                break;
            case 'encaissement':
                $form = $this->createForm(EncaissementType::class, $depotRetrait);
                $returnTwig='encaissement.html.twig';
                $route='depot_retraits_encaissement';
                break;
            case 'decaissement':
                $form = $this->createForm(DecaissementType::class, $depotRetrait);
                $returnTwig='decaissement.html.twig';
                $route='depot_retraits_decaissement';
                break;

            default : $this->addFlash('error', 'Type opération ['.$typeOperation.'] non connu. Seulement [depot,retrait,encaissement,decaissement] accepté');
                return false;
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //cherche le compte pour depot et retrait
            if($typeOperation=='depot' or $typeOperation=='retrait'){
                $compteClient=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['numCompte'=>$depotRetrait->getNumCompteSaisie()]);
                if (!$compteClient){
                    $this->addFlash('error', 'Compte saisie inexistant. Vérifier le numéro de compte');
                    return $this->render('depot_retraits/'.$returnTwig, [
                        'journeeCaisse' => $this->journeeCaisse,
                        'depotRetraits'=>null,
                        'form' => $form->createView(),
                    ]);
                }
                $depotRetrait->setCompteClient($compteClient);
            }else{
                $depotRetrait->setNumCompteSaisie($depotRetrait->getCompteClient()->getNumCompte());
            }

            $genCompta=new GenererCompta($em);
            $ok=$genCompta->genComptaDepotRetrait($depotRetrait, $this->journeeCaisse, $typeOperation);
            if (!$ok){
                $this->addFlash('error', $genCompta->getErrMessage());
                return $this->render('depot_retraits/'.$returnTwig, [
                    'journeeCaisse' => $this->journeeCaisse,
                    'depotRetraits'=>null,
                    'form' => $form->createView(),
                ]);
            }
            $this->journeeCaisse->addDepotRetrait($depotRetrait);
            $em->persist($this->journeeCaisse);
            $em->flush();

            //envoi mail si non compte interne
            if ($depotRetrait->getCompteClient()->getTypeCompte()!=Comptes::INTERNE){
                $message_object='WARISSO - Confirmation de '.strtoupper($typeOperation);
                $message = (new \Swift_Message($message_object))
                    ->setFrom('warisso-confirm@yesbo.bf')
                    ->setTo($depotRetrait->getCompteClient()->getClient()->getEmail())
                    ->setBody( $this->renderView('depot_retraits/recu_depot_retrait.html.twig',
                        ['depotRetrait'=>$depotRetrait,'solde' => $depotRetrait->getCompteClient()->getSoldeCourant(),'typeOperation'=>$typeOperation]
                    ),'text/html' );

                $mailer->send($message);
                return $this->render('depot_retraits/recu_depot_retrait.html.twig', ['depotRetrait'=>$depotRetrait, 'solde'=>null, 'journeeCaisse'=>$this->journeeCaisse, 'typeOperation'=>$typeOperation]);
            }
            return $this->redirectToRoute($route);
        }

        if ($request->isXmlHttpRequest()){

            $num=$request->get('num');
            $comptes=$this->getDoctrine()->getManager()->getRepository(Comptes::class)->findOneBy(['numCompte'=>$num]);

            $compte=[
                //['client'=>$comptes?$comptes->getClient()->getPrenom().' '.$comptes->getClient()->getNom():'','intitule'=>$comptes?$comptes->getIntitule():'']
                ['client'=>$comptes?$comptes->getIntitule():'']
            ];

            $data = ["compte"=>$compte];

            return new JsonResponse($data);
        }
        $depotRetraits=$this->getDoctrine()->getRepository(DepotRetraits::class)->findByJourneeCaisse($this->journeeCaisse);
        return $this->render('depot_retraits/'.$returnTwig, [
            'journeeCaisse' => $this->journeeCaisse,
            'depotRetraits'=>$depotRetraits,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="depot_retraits_show", methods="GET|POST")
     */
    public function liste(Request $request,JourneeCaisses $journeeCaisse): Response
    {

        $depotRetraits=$this->getDoctrine()->getRepository(DepotRetraits::class)->findByJourneeCaisse($journeeCaisse);
        return $this->render('depot_retraits/liste.html.twig',
            [
                'depotRetraits' => $depotRetraits,
                'journeeCaisse' => $journeeCaisse,

            ]);
    }
}
