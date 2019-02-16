<?php

namespace App\Controller;

use App\Entity\Comptes;
use App\Entity\DepotRetraits;
use App\Form\DepotType;
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
     * @Route("/depot", name="depot_retraits_depot", methods="GET|POST")
     */
    public function deposer(Request $request): Response
    {
        return $this->depotRetrait($request,true);

    }

    /**
     * @Route("/retrait", name="depot_retraits_retrait", methods="GET|POST")
     */
    public function retirer(Request $request): Response
    {
        return $this->depotRetrait($request,false);
    }
    /**
     * @Route("/{id}", name="depot_retraits_show", methods="GET")
     */
    public function show(DepotRetraits $depotRetrait): Response
    {
        return $this->render('depot_retraits/show.html.twig', ['depot_retrait' => $depotRetrait]);
    }

    /**
     * @Route("/{id}/edit", name="depot_retraits_edit", methods="GET|POST")
     */
    public function edit(Request $request, DepotRetraits $depotRetrait): Response
    {
        $form = $this->createForm(DepotRetraitsType::class, $depotRetrait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('depot_retraits_index', ['id' => $depotRetrait->getId()]);
        }

        return $this->render('depot_retraits/edit.html.twig', [
            'depot_retrait' => $depotRetrait,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="depot_retraits_delete", methods="DELETE")
     */
    public function delete(Request $request, DepotRetraits $depotRetrait): Response
    {
        if ($this->isCsrfTokenValid('delete'.$depotRetrait->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($depotRetrait);
            $em->flush();
        }

        return $this->redirectToRoute('depot_retraits_index');
    }

    private function getIntituleCompteAjax(Request $request){
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
    }

    private function checkCompteSaise(DepotRetraits $depotRetrait){
        $compteClient=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['numCompte'=>$depotRetrait->getNumCompteSaisie()]);
        if (!$compteClient){
            $this->addFlash('error', 'Compte saisie inexistant. Vérifier le numéro de compte');
            //return $this->redirectToRoute('depot_retraits_depot');
            return false;
        }
        $depotRetrait->setCompteClient($compteClient);
        return true;
    }

    private function depotRetrait(Request $request,$depot=true){

        $depotRetrait = new DepotRetraits();
        $depotRetrait->setDateOperation(new \DateTime());
        if ($depot){
            $form = $this->createForm(DepotType::class, $depotRetrait);
            $returnTwig='depot.html.twig';
        }else{
            $form = $this->createForm(RetraitType::class, $depotRetrait);
            $returnTwig='retrait.html.twig';
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
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
           
            $genCompta=new GenererCompta($em);
            $ok=$genCompta->genComptaDepotRetrait($depotRetrait, $this->journeeCaisse);
            if (!$ok){
                $this->addFlash('error', $genCompta->getErrMessage());
                //return $this->redirectToRoute('depot_retraits_depot');
                return $this->render('depot_retraits/'.$returnTwig, [
                    'journeeCaisse' => $this->journeeCaisse,
                    'depotRetraits'=>null,
                    'form' => $form->createView(),
                ]);
            }
            $this->journeeCaisse->addDepotRetrait($depotRetrait);
            $em->persist($this->journeeCaisse);
            $em->flush();

            return $this->redirectToRoute('journee_caisses_gerer');
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
}
