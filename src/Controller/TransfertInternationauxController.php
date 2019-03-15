<?php

namespace App\Controller;

use App\Entity\Caisses;
use App\Entity\CriteresDates;
use App\Entity\JourneeCaisses;
use App\Entity\SystemTransfert;
use App\Entity\TransfertInternationaux;
use App\Form\CriteresRecherchesJourneeCaissesType;
use App\Form\EmissionsType;
use App\Form\ReceptionsType;
use App\Form\TransfertInternationauxType;
use App\Form\TransfertType;
use App\Utils\SessionUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Builder\ValidationBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//use Webmozart\Assert\Assert;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/transfert/internationaux")
 */
class TransfertInternationauxController extends Controller
{
    private $journeeCaisse;

    public function __construct(SessionUtilisateur $sessionUtilisateur)
    {

        $this->journeeCaisse=$sessionUtilisateur->getJourneeCaisse();
        if(!$this->journeeCaisse){
            return $this->redirectToRoute('app_login');
        }
    }
    /**
     * @Route("/", name="transfert_internationaux_index", methods="GET|POST")
     */
    public function index(Request $request): Response
    {
        //si données envoyé pour post ou get
        $caisse=$request->request->get('caisse')?$request->request->get('caisse'):$request->query->get('caisse');
        $systemTransfert=$request->request->get('systemTransfert')?$request->request->get('systemTransfert'):$request->query->get('systemTransfert');
        $sens=$request->request->get('sens')?$request->request->get('sens'):$request->query->get('sens');
        $dateDebut=$request->query->get('dateDebut');
        $dateFin=$request->query->get('dateFin');

        $criteresRecherches=new CriteresDates();

        if ($dateDebut) $criteresRecherches->setDateDebut(new \DateTime($dateDebut.' 00:00:00'));
        if ($dateFin) $criteresRecherches->setDateFin(new \DateTime($dateFin.' 23:59:59'));

        $form = $this->createForm(CriteresRecherchesJourneeCaissesType::class, $criteresRecherches);
        $form->handleRequest($request);


        $dateFin=$criteresRecherches->getDateFin();
        $dateDebut=$criteresRecherches->getDateDebut();


        $listingTransferts = $this->getDoctrine()
            ->getRepository(TransfertInternationaux::class)
            ->findListingTransferts($dateDebut, $dateFin, $systemTransfert, $caisse, $sens);

        $caisses=$this->getDoctrine()->getRepository(Caisses::class)->findAll();
        $systemTransferts=$this->getDoctrine()->getRepository(SystemTransfert::class)->findAll();

        return $this->render('transfert_internationaux/index.html.twig', [
            'listingTransferts' => $listingTransferts,
            'form' => $form->createView(),
            'caisses'=>$caisses,
            'caisse_id'=>$caisse,
            'systemTransferts'=>$systemTransferts,
            'systemTransfert_id'=>$systemTransfert,
            'sens'=>$sens,
            'criteres'=>$criteresRecherches,
        ]);
    }

    /*
     * @Route("/new", name="transfert_internationaux_new", methods="GET|POST")

    public function new(Request $request): Response
    {
        $transfertInternationaux = new TransfertInternationaux();
        $form = $this->createForm(TransfertInternationauxType::class, $transfertInternationaux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($transfertInternationaux);
            $em->flush();

            return $this->redirectToRoute('transfert_internationaux_index');
        }

        return $this->render('transfert_internationaux/new.html.twig', [
            'transfert_internationaux' => $transfertInternationaux,
            'form' => $form->createView(),
        ]);
    }
*/
    /**
     * @Route("/saisieTransfert", name="transfert_internationaux_saisie", methods="GET|POST")
     */
    public function saisieTransfert(Request $request, $envoi = true): Response
    {
        if($this->journeeCaisse->getStatut()!=JourneeCaisses::ENCOURS){
            $this->addFlash('error','Aucune journée ouverte. Merci d\'ouvrir une journée avant de continuer');
            return $this->redirectToRoute('journee_caisses_gerer');
        }
        $em =$this->getDoctrine();
         
        $form= $this->createForm(TransfertType::class, $this->journeeCaisse);
        $form->handleRequest($request);
        //dump($form);die();
        if ($form->isSubmitted() && $form->isValid() ) {
            //dump($this->journeeCaisse);die();
            $this->journeeCaisse->maintenirTransfertsInternationaux();
            $em = $this->getDoctrine()->getManager();
            $em->persist($this->journeeCaisse);
            $em->flush();

            //dump($request->request);die();
            if ($request->request->has('enregistreretfermer')){
                return $this->redirectToRoute('journee_caisses_gerer');
            }
        }

        return $this->render('transfert_internationaux/ajout.html.twig', [
            'form' => $form->createView(),
            'operation'=>'',
            //'envoi'=>$envoi,
            'journeeCaisse'=>$this->journeeCaisse
        ]);
    }

    private function ajout(Request $request, $envoi = true): Response
    {
        $em =$this->getDoctrine();
        ($envoi)?$this->journeeCaisse->setSensTransfert(TransfertInternationaux::ENVOI):
            $this->journeeCaisse->setSensTransfert(TransfertInternationaux::RECEPTION);
        //dump($this->journeeCaisse->getSensTransfert()); die();
        $form = ($envoi)?$this->createForm(EmissionsType::class, $this->journeeCaisse):$this->createForm(ReceptionsType::class, $this->journeeCaisse);
        $form->handleRequest($request);
        //dump($form);die();
        if ($form->isSubmitted() && $form->isValid() ) {
            //dump($this->journeeCaisse);die();
            $em = $this->getDoctrine()->getManager();
            $em->persist($this->journeeCaisse);
            $em->flush();
        }

        return $this->render('transfert_internationaux/ajout.html.twig', [
            'form' => $form->createView(),
            'operation'=>'',
            'envoi'=>$envoi,
            'journeeCaisse'=>$this->journeeCaisse
        ]);
    }

    /*
     * @Route("/envoi", name="transfert_internationaux_envoi", methods="GET|POST|UPDATE")
     */
    public function envoi(Request $request): Response
    {
        return $this->ajout($request, true);
    }
    /*
     * @Route("/reception", name="transfert_internationaux_reception", methods="GET|POST|UPDATE")
     */
    public function reception(Request $request): Response
    {
        return $this->ajout($request, false);
    }

    /**
     * @Route("/{id}/detail", name="transfert_internationaux_show", methods="GET")
     */
    public function show(Request $request, TransfertInternationaux $transfertInternationaux): Response
    {
        return $this->render('transfert_internationaux/show.html.twig', ['transfert_internationaux' => $transfertInternationaux,
            'dateDebut'=>$request->query->get('dateDebut'),
            'dateFin'=>$request->query->get('dateFin'),
            'systemTransfert'=>$request->query->get('systemTransfert'),
        ]);
    }
    /*
     * @Route("/{id}", name="transfert_internationaux_show", methods="GET|POST")

    public function liste(JourneeCaisses $journeeCaisse): Response
    {
        return $this->render('transfert_internationaux/liste.html.twig', ['journeeCaisse' => $journeeCaisse]);
    }
    */
    /**
     * @Route("/{id}/edit", name="transfert_internationaux_edit", methods="GET|POST")
     */
    public function edit(Request $request, TransfertInternationaux $transfertInternationaux): Response
    {
        $form = $this->createForm(TransfertInternationauxType::class, $transfertInternationaux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('transfert_internationaux_index',[
                'dateDebut'=>$request->query->get('dateDebut'),
                'dateFin'=>$request->query->get('dateFin'),
                'systemTransfert'=>$request->query->get('systemTransfert'),
        ]);
            //return $this->redirectToRoute('transfert_internationaux_edit', ['id' => $transfertInternationaux->getId()]);
        }

        return $this->render('transfert_internationaux/edit.html.twig', [
            'transfert_internationaux' => $transfertInternationaux,
            'form' => $form->createView(),
            'dateDebut'=>$request->query->get('dateDebut'),
            'dateFin'=>$request->query->get('dateFin'),
            'systemTransfert'=>$request->query->get('systemTransfert'),
        ]);
    }

    /**
     * @Route("/{id}", name="transfert_internationaux_delete", methods="DELETE")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function delete(Request $request, TransfertInternationaux $transfertInternationaux): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transfertInternationaux->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($transfertInternationaux);
            $em->flush();
            $this->addFlash('success','Suppression de transfert de montant ['.$transfertInternationaux->getMTransfert().'] effectuée avec succes !');
        }

        return $this->redirectToRoute('transfert_internationaux_index',[
            'dateDebut'=>$request->query->get('dateDebut'),
            'dateFin'=>$request->query->get('dateFin'),
            'systemTransfert'=>$request->query->get('systemTransfert'),
        ]);
    }
}
