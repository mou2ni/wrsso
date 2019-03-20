<?php

namespace App\Controller;

use App\Entity\DepotRetraits;
use App\Entity\InterCaisses;
use App\Entity\JourneeCaisses;
use App\Entity\RecetteDepenses;
use App\Form\InterCaissesType;
use App\Form\RecetteDepensesIntercaissesType;
use App\Utils\GenererCompta;
use App\Utils\SessionUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/inter/caisses")
 */
class InterCaissesController extends Controller
{
    public $totalE=0;
    public $totalR=0;

    private $journeeCaisse;
    private $utilisateur;
    private $caisse;

    public function __construct(SessionUtilisateur $sessionUtilisateur)
    {
        $this->utilisateur=$sessionUtilisateur->getUtilisateur();
        $this->caisse=$sessionUtilisateur->getLastCaisse();
        $this->journeeCaisse=$sessionUtilisateur->getJourneeCaisse();
        if(!$this->journeeCaisse){
            return $this->redirectToRoute('app_login');
        }
    }
    /**
     * @Route("/", name="inter_caisses_index", methods="GET")
     */
    public function index(): Response
    {
        $interCaisses = $this->getDoctrine()
            ->getRepository(InterCaisses::class)
            ->findAll();

        return $this->render('inter_caisses/index.html.twig', ['inter_caisses' => $interCaisses]);
    }

    /**
     * @Route("/ajout", name="intercaisses_ajout", methods="GET|POST|UPDATE")
     */
    public function ajout(Request $request): Response
    {
        if($this->journeeCaisse->getStatut()!=JourneeCaisses::ENCOURS or
            $this->utilisateur->getId()!=$this->journeeCaisse->getUtilisateur()->getId()){
            $this->addFlash('error','Aucune journée ouverte. Merci d\'ouvrir une journée avant de continuer');
            return $this->redirectToRoute('journee_caisses_gerer');
        }

        $em =$this->getDoctrine();
        $operation=$request->request->get('_operation');
        $interCaiss = new InterCaisses();
        //$interCaiss->setJourneeCaisseEntrant($this->journeeCaisse)->setStatut($interCaiss::INITIE);
        $interCaiss->setJourneeCaisseInitiateur($this->journeeCaisse)->setStatut($interCaiss::INITIE);
        $form = $this->createForm(InterCaissesType::class, $interCaiss,['dateComptable'=>$this->journeeCaisse->getDateComptable(),'myJournee'=>$this->journeeCaisse]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //dump($interCaiss);die();

            if ($interCaiss->getJourneeCaisseEntrant()->getId()==$this->journeeCaisse->getId())
                $interCaiss=$this->valider($interCaiss, InterCaisses::VALIDATION_AUTO);

            $this->getDoctrine()->getManager()->persist($interCaiss);
            $this->getDoctrine()->getManager()->flush();

            if($request->request->has('enregistreretfermer')){
                return $this->redirectToRoute('journee_caisses_gerer');
            }
            return $this->redirectToRoute('intercaisses_ajout');


        }
        $myIntercaisses=$this->getDoctrine()->getRepository(Intercaisses::class)->findMyIntercaisses($this->journeeCaisse);
        return $this->render('intercaisses/ajout.html.twig', [
            'journeeCaisse' => $this->journeeCaisse,
            //'totalR'=>$this->totalR,
            //'totalE'=>$this->totalE,
            'form' => $form->createView(),
            'intercaisse'=>$interCaiss,
            'myIntercaisses'=>$myIntercaisses
        ]);
    }

    /**
     * @Route("/autorise/{id}", name="intercaisses_autoriser", methods="GET|POST|UPDATE")
     */
    public function autoriser(Request $request, InterCaisses $interCaisse): Response
    {
        if($this->journeeCaisse->getStatut()!=JourneeCaisses::ENCOURS or
            $this->utilisateur->getId()!=$this->journeeCaisse->getUtilisateur()->getId()){
            $this->addFlash('error','Aucune journée ouverte. Merci d\'ouvrir une journée avant de continuer');
            return $this->redirectToRoute('journee_caisses_gerer');
        }
        if (!($interCaisse->getStatut() == InterCaisses::INITIE or $interCaisse->getStatut() == InterCaisses::VALIDE or $interCaisse->getStatut() == InterCaisses::VALIDATION_AUTO))
        {
            $this->addFlash('error', 'Statut intercaisse non modifiable.');
            return $this->redirectToRoute('intercaisses_ajout');
        }
        //dump($request->request); die();

        if ($request->getMethod()=='UPDATE'){ //Actions sur les intercaisses
            //sécuriser l'opération avec un token
            if ($this->isCsrfTokenValid('update'.$interCaisse->getId(), $request->request->get('_token'))) {
                //bouton "annuler" cliqué
                if ( $request->request->has('annuler')){
                    if ($interCaisse->getStatut()==InterCaisses::VALIDE or $interCaisse->getStatut()==InterCaisses::VALIDATION_AUTO){
                        $interCaisse=$this->annulerValide($interCaisse);
                        if($interCaisse==false) return $this->redirectToRoute('intercaisses_ajout');
                    }
                    $interCaisse->setStatut(InterCaisses::ANNULE);
                }
                //bouton "valider" cliqué
                if ( $request->request->has('valider')){
                    $interCaisse=$this->valider($interCaisse);
                    if($interCaisse==false) return $this->redirectToRoute('intercaisses_ajout');
                }
                $this->getDoctrine()->getManager()->persist($interCaisse);
                $this->getDoctrine()->getManager()->flush();
            }
            return $this->redirectToRoute('intercaisses_ajout');
        }
    }

    /**
     * @Route("/{id}/comptaRecetteDepenses", name="inter_caisses_comptabiliser_recetteDepenses", methods="GET|POST|COMPTABILISE")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function comptaIntercaisseRecetteDepenses(Request $request, InterCaisses $interCaisse): Response
    {
        //dump($interCaisse);die();
        if (!($interCaisse->getStatut()==InterCaisses::VALIDE or $interCaisse->getStatut()==InterCaisses::VALIDATION_AUTO)){
            $this->addFlash('error', 'Intercaisse : '.$interCaisse.' de '.$interCaisse->getMIntercaisse().' non validé ou déjà comptabilisé.');
            return $this->redirectToRoute('intercaisses_ajout');
        }
        $recetteDepense=new RecetteDepenses();
        $recetteDepense->setStatut(RecetteDepenses::STAT_INITIAL)
            ->setMSaisie($interCaisse->getMIntercaisse())
            ->setEstComptant(true)
            ->setLibelle($interCaisse->getObservations());
        $form = $this->createForm(RecetteDepensesIntercaissesType::class, $recetteDepense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $genCompta=new GenererCompta($em);
            $recetteDepense->comptabiliserNouveau($genCompta, $this->journeeCaisse);
            if ($genCompta->getE()) {
                $this->addFlash('error', $genCompta->getErrMessage());
                return $this->redirectToRoute('recette_depenses_saisie');
            }
            //vérifier la cohérence entre l'ecriture comptable et l'intercaisse
            if ($this->isCoherent($recetteDepense,$interCaisse)){
                $interCaisse->setRecetteDepense($recetteDepense);
                ($recetteDepense->getMRecette())?$interCaisse->setStatut(InterCaisses::COMPTA_PRODUIT)
                    :$interCaisse->setStatut(InterCaisses::COMPTA_CHARGE);
                //$em->persist($recetteDepense);
                $this->journeeCaisse->addRecetteDepense($recetteDepense);
                $em->persist($this->journeeCaisse);
                $em->flush();
                if ($request->request->has('save_and_close')){
                    return $this->redirectToRoute('journee_caisses_gerer');
                }
                return $this->redirectToRoute('intercaisses_ajout');
            }
        }
        return $this->render('intercaisses/recette_depenses.html.twig', [
            'recetteDepense' => $recetteDepense,
            'form' => $form->createView(),
            'journeeCaisse'=>$this->journeeCaisse,
            //'mSaisie'=>$recetteDepense->getMSaisie(),
        ]);
    }

    /**
     * @Route("/{id}/retourCompense", name="inter_caisses_comptabiliser_retour_compense", methods="GET|POST|COMPTABILISE")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function comptaIntercaisseCompense(Request $request, InterCaisses $interCaisse): Response
    {
        $retourCompense=new DepotRetraits();
    }
    /*
     * @Route("/new", name="inter_caisses_new", methods="GET|POST")
     
    public function new(Request $request): Response
    {
        $interCaiss = new InterCaisses();
        $form = $this->createForm(InterCaissesType::class, $interCaiss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($interCaiss);
            $em->flush();

            return $this->redirectToRoute('inter_caisses_index');
        }

        return $this->render('inter_caisses/new.html.twig', [
            'inter_caiss' => $interCaiss,
            'form' => $form->createView(),
        ]);
    }*/
    /**
     * @Route("/{id}", name="inter_caisses_show", methods="GET|POST")
     */
    public function show(JourneeCaisses $journeeCaisse): Response
    {
        $myIntercaisses=$this->getDoctrine()->getRepository(Intercaisses::class)->findMyIntercaisses($journeeCaisse);
        return $this->render('intercaisses/show.html.twig', ['journeeCaisse' => $journeeCaisse,
            'myIntercaisses'=>$myIntercaisses ]);
    }

    /*
     * @Route("/{id}/edit", name="inter_caisses_edit", methods="GET|POST")
     
    public function edit(Request $request, InterCaisses $interCaiss): Response
    {
        $form = $this->createForm(InterCaissesType::class, $interCaiss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('inter_caisses_edit', ['id' => $interCaiss->getId()]);
        }

        return $this->render('inter_caisses/edit.html.twig', [
            'inter_caiss' => $interCaiss,
            'form' => $form->createView(),
        ]);
    }*/

    /*
     * @Route("/{id}", name="inter_caisses_delete", methods="DELETE")
     
    public function delete(Request $request, InterCaisses $interCaiss): Response
    {
        if ($this->isCsrfTokenValid('delete'.$interCaiss->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($interCaiss);
            $em->flush();
        }

        return $this->redirectToRoute('inter_caisses_index');
    }*/

    private function valider(InterCaisses $interCaisse, $statut=InterCaisses::VALIDE){
        $interCaisse->setStatut($statut);
        $interCaisse->getJourneeCaisseEntrant()->updateM('mIntercaisses', $interCaisse->getMIntercaisse());
        $interCaisse->getJourneeCaisseSortant()->updateM('mIntercaisses', -$interCaisse->getMIntercaisse());
        /*$genCompta=new GenererCompta($this->getDoctrine()->getManager());
        if (!$genCompta->genComptaIntercaisse($this->utilisateur,$interCaisse->getJourneeCaisseEntrant()->getCaisse(), $interCaisse->getJourneeCaisseSortant()->getCaisse(),$interCaisse->getMIntercaisse(),$interCaisse->getJourneeCaisseInitiateur())){
            $this->addFlash('error', $genCompta->getErrMessage());
            return false;
        };

        $interCaisse->setTransaction($genCompta->getTransactions()[0]);*/
        return $interCaisse;
    }
    
    private function annulerValide(InterCaisses $interCaisse){
        if( $interCaisse->getJourneeCaisseEntrant()->getStatut() != JourneeCaisses::ENCOURS){
            $this->addFlash('error', 'Annulation Impossible. Caisse Entrant ['.$interCaisse->getJourneeCaisseEntrant()->getCaisse().'-'.$interCaisse->getJourneeCaisseEntrant()->getUtilisateur().'] déjà fermé ! ! ! ');
            return false;
        }
        if( $interCaisse->getJourneeCaisseSortant()->getStatut() != JourneeCaisses::ENCOURS){
            $this->addFlash('error', 'Annulation Impossible. Caisse Sortant ['.$interCaisse->getJourneeCaisseSortant()->getCaisse().'-'.$interCaisse->getJourneeCaisseSortant()->getUtilisateur().'] déjà fermé ! ! ! ');
            return false;
        }
        $interCaisse->getJourneeCaisseEntrant()->updateM('mIntercaisses', -$interCaisse->getMIntercaisse());
        $interCaisse->getJourneeCaisseSortant()->updateM('mIntercaisses', $interCaisse->getMIntercaisse());
        $interCaisse->setStatut(InterCaisses::ANNULE);

       /* $genCompta=new GenererCompta($this->getDoctrine()->getManager());
        if (!$genCompta->genComptaIntercaisse($this->utilisateur,$interCaisse->getJourneeCaisseSortant()->getCaisse(), $interCaisse->getJourneeCaisseEntrant()->getCaisse(), $interCaisse->getMIntercaisse(),$interCaisse->getJourneeCaisseInitiateur(), $interCaisse->getTransaction())){
            $this->addFlash('error', $genCompta->getErrMessage());
            return false;
        };*/
        return $interCaisse;
    }

    private function isCoherent(RecetteDepenses $recetteDepense, Intercaisses $interCaisse ){
        if ($recetteDepense->getMDepense() && $interCaisse->getJourneeCaisseSortant()->getId()==$this->journeeCaisse->getId()){
            $this->addFlash('error', 'Intercaisse Sortant ne peut être pour une depense. Vérifier le type d\'operation comptable');
            return false;
        }
        if ($recetteDepense->getMRecette() && $interCaisse->getJourneeCaisseEntrant()->getId()==$this->journeeCaisse->getId()){
            $this->addFlash('error', 'Intercaisse Entrant ne peut être pour une recette. Vérifier le type d\'operation comptable');
            return false;
        }
        return true;
    }
}
