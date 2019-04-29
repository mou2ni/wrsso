<?php

namespace App\Controller;

use App\Entity\Agences;
use App\Entity\Comptes;
use App\Entity\CriteresDates;
use App\Entity\JourneeCaisses;
use App\Entity\RecetteDepenses;
use App\Entity\TypeOperationComptables;
use App\Entity\Utilisateurs;
use App\Form\CriteresDatesType;
use App\Form\RecetteDepenseJourneesType;
use App\Form\RecetteDepensesComptantsType;
use App\Form\RecetteDepensesCorrectionsType;
use App\Form\RecetteDepensesType;
use App\Repository\RecetteDepensesRepository;
use App\Utils\GenererCompta;
use App\Utils\SessionUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/recettedepenses")
 */
class RecetteDepensesController extends Controller
{
    public function __construct(SessionUtilisateur $sessionUtilisateur)
    {
        $this->utilisateur = $sessionUtilisateur->getUtilisateur();
        //dernière caisse ouverte par l'utilisateur ou null si inexistant
        $this->caisse = $sessionUtilisateur->getLastCaisse();
        //dernière journée de la caisse ou null si inexistant
        $this->journeeCaisse = $sessionUtilisateur->getJourneeCaisse();
    }
    /**
     * @Route("/", name="recette_depenses_index", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function index(Request $request): Response
    {
        $agence=$request->request->get('agence')?$request->request->get('agence'):$request->query->get('agence');
        $compteTier=$request->request->get('compteTier')?$request->request->get('compteTier'):$request->query->get('compteTier');
        $utilisateur=$request->request->get('utilisateur')?$request->request->get('utilisateur'):$request->query->get('utilisateur');
        $compteGestion=$request->request->get('compteGestion')?$request->request->get('compteGestion'):$request->query->get('compteGestion');
        $typeOperationComptable=$request->request->get('typeOperationComptable')?$request->request->get('typeOperationComptable'):$request->query->get('typeOperationComptable');
        $statut=$request->request->get('statut')?$request->request->get('statut'):$request->query->get('statut');
        $dateDebut=$request->query->get('dateDebut');
        $dateFin=$request->query->get('dateFin');

        $criteresRecherches=new CriteresDates();

        if ($dateDebut) $criteresRecherches->setDateDebut(new \DateTime($dateDebut.' 00:00:00'));
        else{
            $auj=new \DateTime(); $moisEncours=$auj->format('m'); $annee=$auj->format('Y');
            $criteresRecherches->setDateDebut(new \DateTime($annee.'-'.$moisEncours.'-01 00:00:00'));
        }
        if ($dateFin) $criteresRecherches->setDateFin(new \DateTime($dateFin.' 23:59:59'));
        else{
            $auj=new \DateTime(); $moisEncours=$auj->format('m'); $annee=$auj->format('Y');$moisSuiv=$moisEncours+1;
            $criteresRecherches->setDateFin(new \DateTime($annee.'-'.$moisSuiv.'-00 23:59:59'));
        }

        $form = $this->createForm(CriteresDatesType::class, $criteresRecherches);
        $form->handleRequest($request);

        $listingRecetteDepenses = $this->getDoctrine()
            ->getRepository(RecetteDepenses::class)
            ->findListingRecetteDepenses($criteresRecherches->getDateDebut(), $criteresRecherches->getDateFin(), $compteTier, $compteGestion, $utilisateur, $typeOperationComptable, $statut, null, $agence);

        $compteTiers=$this->getDoctrine()->getRepository(Comptes::class)->findCompteContrePartieDepenseRecettes();
        $utilisateurs=$this->getDoctrine()->getRepository(Utilisateurs::class)->findAll();
        $agences=$this->getDoctrine()->getRepository(Agences::class)->findAll();
        $compteGestions=$this->getDoctrine()->getRepository(Comptes::class)->findCompteGestions();
        $typeOperationComptables=$this->getDoctrine()->getRepository(TypeOperationComptables::class)->findAll();

        return $this->render('recette_depenses/index.html.twig', [
            'recetteDepenses' => $listingRecetteDepenses,
            'form' => $form->createView(),
            'compteTiers'=>$compteTiers,
            'compteTier_id'=>$compteTier,
            'utilisateurs'=>$utilisateurs,
            'utilisateur_id'=>$utilisateur,
            'compteGestions'=>$compteGestions,
            'compteGestion_id'=>$compteGestion,
            'typeOperationComptables'=>$typeOperationComptables,
            'typeOperationComptable_id'=>$typeOperationComptable,
            'statut'=>$statut,
            'agences'=>$agences,
            'agence_id'=>$agence,
            'criteres'=>$criteresRecherches,
        ]);
    }

    /*
     * @Route("/autorisation", name="recette_depenses_autorisation", methods="GET")
     * @Security("has_role('ROLE_COMPTABLE')")

    public function enAutorisation(RecetteDepensesRepository $recetteDepensesRepository): Response
    {
        return $this->render('recette_depenses/autoriser.html.twig', ['recetteDepenses' => $recetteDepensesRepository->findBy(['statut'=>RecetteDepenses::STAT_INITIAL]), 'journeeCaisse'=>null]);
    }*/

    /*
     * @Route("/comptabilisation", name="recette_depenses_comptabilisation", methods="GET")
     * @Security("has_role('ROLE_COMPTABLE')")
     
    public function enComptabilisation(RecetteDepensesRepository $recetteDepensesRepository): Response
    {
        return $this->render('recette_depenses/autoriser.html.twig', ['recetteDepenses' => $recetteDepensesRepository->findRecetteDepenses(), 'journeeCaisse'=>null]);
    }*/

    /**
     * @Route("/saisieComptant", name="recette_depenses_comptant", methods="GET|POST")
     */
    public function ajoutComptant(Request $request): Response
    {
        return $this->ajout($request,'recette_depenses_comptant');
    }

    /**
     * @Route("/saisieaterme", name="recette_depenses_aterme", methods="GET|POST")
     */
    public function ajoutAterme(Request $request): Response
    {
        return $this->ajout($request,'recette_depenses_aterme',false);
    }

    private function ajout(Request $request, $returnRoute, $estComptant=true): Response
    {
        $recetteDepense = new RecetteDepenses();
        $recetteDepense->setUtilisateur($this->utilisateur)->setJourneeCaisse($this->journeeCaisse)->setStatut(RecetteDepenses::STAT_INITIAL)->setEstComptant($estComptant)->setAgence($this->caisse->getAgence());

        /*if ($estComptant){
            if ($this->isGranted('ROLE_COMPTABLE')) $form=$this->createForm(RecetteDepensesType::class, $recetteDepense);
            else $form=$this->createForm(RecetteDepensesComptantsType::class, $recetteDepense);
        }else $form=$this->createForm(RecetteDepensesType::class, $recetteDepense);*/

        $form=$this->createForm(RecetteDepensesType::class, $recetteDepense, ['isComptable'=>$this->isGranted('ROLE_COMPTABLE'),'isComptant'=>$estComptant]);

        $form->handleRequest($request);

        $recetteDepensesComptant=$this->getDoctrine()->getRepository(RecetteDepenses::class)->findBy(['journeeCaisse'=>$this->journeeCaisse, 'estComptant'=>true]);
        $recetteDepensesAterme=$this->getDoctrine()->getRepository(RecetteDepenses::class)->findBy(['journeeCaisse'=>$this->journeeCaisse, 'estComptant'=>false]);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if ($recetteDepense->getMSaisie()>0){
                $recetteDepense->setMRecette($recetteDepense->getMSaisie());
                $ok=$this->valider($recetteDepense, RecetteDepenses::STAT_VALIDATION_AUTO);
                if (!$ok) {
                    return $this->render('recette_depenses/new.html.twig', [
                        'recette_depense' => $recetteDepense,
                        'journeeCaisse'=>$this->journeeCaisse,
                        'recetteDepensesComptant'=>$recetteDepensesComptant,
                        'recetteDepensesAterme'=>$recetteDepensesAterme,
                        'form' => $form->createView(),
                        'estComptant'=>$estComptant,
                    ]);
                }
            }elseif ($recetteDepense->getMSaisie()<0){
                $recetteDepense->setMDepense(-$recetteDepense->getMSaisie());
                if ($this->isGranted('ROLE_COMPTABLE')){ //auto validé si c'est un comptable
                    $ok=$this->valider($recetteDepense, RecetteDepenses::STAT_VALIDATION_AUTO);
                    if (!$ok) return $this->redirectToRoute($returnRoute);
                }
            } else{return $this->redirectToRoute($returnRoute);}

            $em->persist($recetteDepense);
            $em->persist($this->journeeCaisse);
            $em->flush();

            return $this->redirectToRoute('journee_caisses_gerer');

            /*if($request->request->has('save_and_close')){
                return $this->redirectToRoute('journee_caisses_gerer');
            }else {return $this->redirectToRoute('recette_depenses_index');}*/
        }
        return $this->render('recette_depenses/new.html.twig', [
            'recette_depense' => $recetteDepense,
            'journeeCaisse'=>$this->journeeCaisse,
            'recetteDepensesComptant'=>$recetteDepensesComptant,
            'recetteDepensesAterme'=>$recetteDepensesAterme,
            'form' => $form->createView(),
            'estComptant'=>$estComptant,
        ]);
    }

    /**
     * @Route("/autorise/{id}", name="recette_depenses_autoriser", methods="GET|POST|UPDATE")
     */
    public function autoriser(Request $request, RecetteDepenses $recetteDepense): Response
    {
        if (!($recetteDepense->getStatut() == RecetteDepenses::STAT_INITIAL
            or $recetteDepense->getStatut() == RecetteDepenses::STAT_VALIDE
            or $recetteDepense->getStatut() == RecetteDepenses::STAT_VALIDATION_AUTO))
        {
            $this->addFlash('error', 'Statut non modifiable.');
            return $this->redirectToRoute('recette_depenses_comptant');
        }

        if ($request->getMethod()=='UPDATE'){ //Actions sur les intercaisses
            //sécuriser l'opération avec un token
            if ($this->isCsrfTokenValid('update'.$recetteDepense->getId(), $request->request->get('_token'))) {
                //bouton "annuler" cliqué
                if ( $request->request->has('annuler')){
                    if ($recetteDepense->getStatut()==RecetteDepenses::STAT_VALIDE
                        or $recetteDepense->getStatut()==RecetteDepenses::STAT_VALIDATION_AUTO){
                        $recetteDepense=$this->annulerValide($recetteDepense);
                        if($recetteDepense==false) return $this->redirectToRoute('recette_depenses_modif',['id'=>$recetteDepense->getId()]);
                    }
                    $recetteDepense->setStatut(RecetteDepenses::STAT_ANNULE);
                    $recetteDepense->setUtilisateurValidateur($this->utilisateur);
                    $this->getDoctrine()->getManager()->persist($recetteDepense);
                    $this->getDoctrine()->getManager()->flush();
                    return $this->redirectToRoute('recette_depenses_comptant');
                }
                //bouton "valider" cliqué
                if ( $request->request->has('valider')){
                    if ($this->isGranted('ROLE_COMPTABLE'))
                        $ok=$this->valider($recetteDepense);
                    if(!$ok) {
                        return $this->redirectToRoute('recette_depenses_modif',['id'=>$recetteDepense->getId()]);
                        /*if ($recetteDepense->getEstComptant()) return $this->redirectToRoute('recette_depenses_comptant');
                        else return $this->redirectToRoute('recette_depenses_aterme');*/
                    }
                    $this->getDoctrine()->getManager()->persist($recetteDepense);
                    $this->getDoctrine()->getManager()->flush();
                    return $this->redirectToRoute('recette_depenses_index');
                }
            }
            return $this->redirectToRoute('recette_depenses_comptant');
        }
    }

    /**
     * @Route("/comptabilise/{id}", name="recette_depenses_comptabiliser", methods="GET|POST|UPDATE")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function comptabiliser(Request $request, RecetteDepenses $recetteDepense): Response
    {
        $form = $this->createForm(RecetteDepensesType::class, $recetteDepense);
        $form->handleRequest($request);

        if($recetteDepense->getTransaction()!=null){
            $this->addFlash('error', 'Recette-depense déjà comptabilisés.');
            return $this->redirectToRoute('recette_depenses_index');
        }
        if($recetteDepense->getStatut()==RecetteDepenses::STAT_ANNULE){
            $this->addFlash('error', 'Recette-depense annulée. Comptabilisation impossible');
            return $this->redirectToRoute('recette_depenses_index');
        }
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod()=='UPDATE') { 
            //sécuriser l'opération avec un token
            if ($this->isCsrfTokenValid('update' . $recetteDepense->getId(), $request->request->get('_token'))) {
                //bouton "annuler" cliqué
                if ($request->request->has('comptabiliser')) {
                    if ($recetteDepense->getStatut() == RecetteDepenses::STAT_INITIAL) {
                        $recetteDepense = $this->valider($recetteDepense);
                        if ($recetteDepense == false) return $this->redirectToRoute('recette_depenses_modif',['id'=>$recetteDepense->getId()]);
                    }

                    $genCompta = new GenererCompta($this->getDoctrine()->getManager());
                    
                    $compta = $genCompta->genComptaRecetteDepense($this->utilisateur, $recetteDepense);
                    if (!$compta) {
                        $this->addFlash('error', $genCompta->getErrMessage());
                        return $this->redirectToRoute('recette_depenses_modif',['id'=>$recetteDepense->getId()]);

                    }
                    $em->persist($recetteDepense);
                    $em->flush();
                    return $this->redirectToRoute('recette_depenses_index');
                    //return $this->redirectToRoute('transactions_show',['id'=>$recetteDepense->getTransaction()->getId()]);
                }
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($recetteDepense);
            $em->flush();
        }

        return $this->render('recette_depenses/comptabiliser.html.twig', [
            'recette_depense' => $recetteDepense,
            'journeeCaisse' => $this->journeeCaisse,
            'form' => $form->createView(),
        ]);

    }
    
    /**
     * @Route("/saisiegroupeecomptant", name="recette_depenses_comptant_groupee", methods="GET|POST")
     */
    public function saisieComptantGroupee(Request $request):Response
    {
        $form = $this->createForm(RecetteDepenseJourneesType::class, $this->journeeCaisse);
        $form->handleRequest($request);
        //dump($request);die();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //dump($this->journeeCaisse);die();
            foreach ($this->journeeCaisse->getRecetteDepenses() as $recetteDepense) {
                //$genCompta=new GenererCompta($em);
                $recetteDepense->setCompteTier($this->caisse->getCompteOperation());
                if ($recetteDepense->getStatut()==RecetteDepenses::STAT_INITIAL or $recetteDepense->getStatut()==null) {
                    $recetteDepense->setEstComptant(true);
                    /*$ok = $recetteDepense->comptabiliserNouveau($genCompta, $this->journeeCaisse);
                    //$ok=$genCompta->genComptaRecetteDepenseComptant($this->utilisateur,$this->caisse,$recetteDepense,$this->journeeCaisse);
                    if (!$ok) {
                        $this->addFlash('error', $genCompta->getErrMessage());
                        return $this->render('recette_depenses/recette_depense_journee.html.twig', ['journeeCaisse' => $this->journeeCaisse, 'form' => $form->createView(),]);
                    }*/
                }
                //$recetteDepense->setTransaction($genCompta->getTransactions()[0]);
                //$recetteDepense->setStatut(RecetteDepenses::STAT_COMPTA);
                //$em->persist($recetteDepense);
            }
            $this->journeeCaisse->maintenirRecetteDepenses();
            $em->persist($this->journeeCaisse);
            $em->flush();
            $this->addFlash('success','Depenses et recettes bien enregistré');

            if($request->request->has('save_and_close')){
                return $this->redirectToRoute('compta_saisie_cmd');
            }
            return $this->redirectToRoute('recette_depenses_comptant_groupee');
        }

        return $this->render('recette_depenses/recette_depense_journee.html.twig', [
            //'recette_depense' => $recetteDepense,
            'journeeCaisse' => $this->journeeCaisse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/saisiegroupeeaterme", name="recette_depenses_aterme_groupee", methods="GET|POST")
     */
    public function saisieAtermeGroupee(Request $request):Response
    {

    }

    /**
     * @Route("/{id}", name="recette_depenses_details", methods="GET")
     */
    public function show(RecetteDepenses $recetteDepense): Response
    {
        return $this->render('recette_depenses/show.html.twig', ['recette_depense' => $recetteDepense]);
    }

    /**
     * @Route("/{id}/modif", name="recette_depenses_modif", methods="GET|POST")
     */
    public function edit(Request $request, RecetteDepenses $recetteDepense): Response
    {
        //$recetteDepenseOld=$recetteDepense;
        if($recetteDepense->getStatut()==RecetteDepenses::STAT_COMPTA and !$this->isGranted('ROLE_COMPTABLE')){

            $this->addFlash('error', 'Impossible de modifier une recette-depense déjà comptabilisé');
            return $this->redirectToRoute('recette_depenses_comptant');
        }

        $form = $this->createForm(RecetteDepensesType::class, $recetteDepense,['isComptant'=>$recetteDepense->getEstComptant(),'isComptable'=>$this->isGranted('ROLE_COMPTABLE'),'statut'=>$recetteDepense->getStatut()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$recetteDepense->setCompteTier($recetteDepenseOld->getCompteTier());
            if ($recetteDepense->getUtilisateur()->getId() == $this->utilisateur->getId() and $recetteDepense->getStatut()!=RecetteDepenses::STAT_COMPTA){
                if($recetteDepense->getMSaisie()>0){
                    $recetteDepense->setMRecette($recetteDepense->getMSaisie())->setMDepense(0)
                        ->setStatut(RecetteDepenses::STAT_VALIDATION_AUTO);
                }else{
                    $recetteDepense->setMDepense($recetteDepense->getMSaisie())->setMRecette(0)
                    ->setStatut(RecetteDepenses::STAT_INITIAL);
                }
            }
            if ($this->isGranted('ROLE_COMPTABLE')){
                $genCompta=new GenererCompta($this->getDoctrine()->getManager());
                if(!$genCompta->checkCoherenceRececetteDepenses($recetteDepense)){
                    $this->addFlash('error',$genCompta->getErrMessage());
                    return $this->render('recette_depenses/edit.html.twig', [
                        'recette_depense' => $recetteDepense,
                        'journeeCaisse' => $this->journeeCaisse,
                        'form' => $form->createView(),
                    ]);
                }
            }
            
            $recetteDepense->getJourneeCaisse()->maintenirRecetteDepenses();
            if($recetteDepense->getTransaction())
                $recetteDepense->getTransaction()->setDateTransaction($recetteDepense->getDateOperation())
                    ->setLibelle($recetteDepense->getLibelle());

            $this->getDoctrine()->getManager()->flush();

            if ($this->isGranted('ROLE_COMPTABLE'))
                return $this->redirectToRoute('recette_depenses_index',['dateDebut'=>$recetteDepense->getDateOperation()->format('Y-m-d')]);
            else return $this->redirectToRoute('recette_depenses_comptant');
        }

        return $this->render('recette_depenses/edit.html.twig', [
            'recette_depense' => $recetteDepense,
            'journeeCaisse' => $this->journeeCaisse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recette_depenses_suppr", methods="DELETE")
     */
    public function delete(Request $request, RecetteDepenses $recetteDepense): Response
    {
        if($recetteDepense->getStatut()!=RecetteDepenses::STAT_COMPTA and $recetteDepense->getJourneeCaisse()->getStatut()==JourneeCaisses::ENCOURS){
            if ($this->isCsrfTokenValid('delete'.$recetteDepense->getId(), $request->request->get('_token'))) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($recetteDepense);
                $recetteDepense->getJourneeCaisse()->removeRecetteDepense($recetteDepense);
                $em->flush();
            }
        }else{
            $this->addFlash('error','Recette depenses déjà validées ou journeeCaisse cloturée. Impossible de le supprimer ! ! !');
        }
        if ($this->isGranted('ROLE_COMPTABLE'))
            return $this->redirectToRoute('recette_depenses_index');
        else return $this->redirectToRoute('recette_depenses_comptant');
    }

    private function valider(RecetteDepenses $recetteDepense, $statut=RecetteDepenses::STAT_VALIDE){

        if ($statut==RecetteDepenses::STAT_VALIDE and $recetteDepense->getUtilisateur()->getId()==$this->utilisateur->getId()){
            $this->addFlash('error', 'L\'utilisateur ['.$this->utilisateur.'] Impossible de valider une depenses dont on initiateur !');
            return false;
        }
        $recetteDepense->setStatut($statut);

        /*if($this->isGranted('ROLE_COMPTABLE')){
            $genCompta=new GenererCompta($this->getDoctrine()->getManager());
            if (!$genCompta->checkCoherenceRececetteDepenses($recetteDepense)){
                $this->addFlash('error', $genCompta->getErrMessage());
                return false;
            }
        }*/
        /**/
        if ($recetteDepense->getEstComptant()) {
            $recetteDepense->getJourneeCaisse()->updateM('mRecette', $recetteDepense->getMRecette());
            $recetteDepense->getJourneeCaisse()->updateM('mDepense', $recetteDepense->getMDepense());
        }else{

            $recetteDepense->getJourneeCaisse()->updateM('mRecetteAterme', $recetteDepense->getMRecette());
            $recetteDepense->getJourneeCaisse()->updateM('mDepenseAterme', $recetteDepense->getMDepense());
        }
        //$recetteDepense->getJourneeCaisse()->maintenirRecetteDepenses();
        $recetteDepense->setUtilisateurValidateur($this->utilisateur);

        return true;
    }

    private function annulerValide(RecetteDepenses $recetteDepense){
        //A implementer
        $recetteDepense->getJourneeCaisse()->updateM('mRecette', -$recetteDepense->getMRecette());
        $recetteDepense->getJourneeCaisse()->updateM('mDepense', -$recetteDepense->getMDepense());
        return true;
    }

    /*private function estCoherent(RecetteDepenses $recetteDepense){
        return true;
    }*/
}
