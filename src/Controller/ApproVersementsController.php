<?php

namespace App\Controller;

use App\Entity\ApproVersements;
use App\Entity\JourneeCaisses;
use App\Form\ApproVersementsModifType;
use App\Form\ApproVersementsType;
use App\Repository\ApproVersementsRepository;
use App\Utils\GenererCompta;
use App\Utils\SessionUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/approversements")
 */
class ApproVersementsController extends Controller
{
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
     * @Route("/", name="appro_versements_index", methods="GET")
     * @Security("has_role('ROLE_GUICHETIER')")
     */
    public function index(Request $request): Response
    {
        $limit=20;
        $_page=$request->query->get('_page');
        $classe=$request->query->get('master');
        $offset = ($_page)?($_page-1)*$limit:0;
        $liste = $this->getDoctrine()
            ->getRepository(ApproVersements::class)
            ->liste($offset,$limit,$classe);
        $pages = round(count($liste)/$limit);
        
        return $this->render('appro_versements/index.html.twig', ['approVersements' => $liste, 'pages'=>$pages]);
    }

    /**
     * @Route("/ajout", name="appro_versements_ajout", methods="GET|POST")
     */
    public function ajout(Request $request): Response
    {
        if($this->journeeCaisse->getStatut()!=JourneeCaisses::ENCOURS or
            $this->utilisateur->getId()!=$this->journeeCaisse->getUtilisateur()->getId()){
            $this->addFlash('error','Aucune journée ouverte. Merci d\'ouvrir une journée avant de continuer');
            return $this->redirectToRoute('journee_caisses_gerer');
        }

        $em =$this->getDoctrine();
        $approVersement = new ApproVersements();
        $approVersement->setUtilisateur($this->utilisateur)->setStatut($approVersement::STAT_INITIAL);
        $form = $this->createForm(ApproVersementsType::class, $approVersement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($approVersement->getMSaisie()>0){
                //$approVersement->setMAppro($approVersement->getMSaisie());
                //$approVersement->setMVersement(0);
                $approVersement->setMApproVersement($approVersement->getMSaisie());
                $approVersement->setJourneeCaisseEntrant($this->journeeCaisse);
                $approVersement->setJourneeCaisseSortant($approVersement->getJourneeCaissePartenaire());
                $approVersement=$this->valider($approVersement, ApproVersements::STAT_VALIDATION_AUTO);
                if (!$approVersement) return $this->redirectToRoute('appro_versements_ajout');
            }elseif ($approVersement->getMSaisie()<0){
                //$approVersement->setMAppro(0);
                //$approVersement->setMVersement(-$approVersement->getMSaisie());
                $approVersement->setMApproVersement(-$approVersement->getMSaisie());
                $approVersement->setJourneeCaisseEntrant($approVersement->getJourneeCaissePartenaire());
                $approVersement->setJourneeCaisseSortant($this->journeeCaisse);
                if($this->isGranted('ROLE_COMPTABLE')){
                    $approVersement=$this->valider($approVersement, ApproVersements::STAT_VALIDATION_AUTO);
                    if (!$approVersement) return $this->redirectToRoute('appro_versements_ajout');
                }
            } else{return $this->redirectToRoute('appro_versements_ajout');}

            $approVersement->setDateOperation(new \DateTime());

            $this->getDoctrine()->getManager()->persist($approVersement);
            $this->getDoctrine()->getManager()->flush();

            if($request->request->has('save_and_close')){
                return $this->redirectToRoute('journee_caisses_gerer');
            }
            return $this->redirectToRoute('appro_versements_ajout');
        }

        return $this->render('appro_versements/new.html.twig', [
            'appro_versement' => $approVersement,
            'journeeCaisse' => $this->journeeCaisse,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/autorise/{id}", name="appro_versements_autoriser", methods="GET|POST|UPDATE")
     */
    public function autoriser(Request $request, ApproVersements $approVersement): Response
    {
        if($this->journeeCaisse->getStatut()!=JourneeCaisses::ENCOURS or
            $this->utilisateur->getId()!=$this->journeeCaisse->getUtilisateur()->getId()){
            $this->addFlash('error','Aucune journée ouverte. Merci d\'ouvrir une journée avant de continuer');
            return $this->redirectToRoute('journee_caisses_gerer');
        }
        if (!($approVersement->getStatut() == ApproVersements::STAT_INITIAL
            or $approVersement->getStatut() == ApproVersements::STAT_VALIDE
            or $approVersement->getStatut() == ApproVersements::STAT_VALIDATION_AUTO))
        {
            $this->addFlash('error', 'Statut Appro versement non modifiable.');
            return $this->redirectToRoute('appro_versements_ajout');
        }

        if ($request->getMethod()=='UPDATE'){ //Actions sur les intercaisses
            //sécuriser l'opération avec un token
            if ($this->isCsrfTokenValid('update'.$approVersement->getId(), $request->request->get('_token'))) {
                //bouton "annuler" cliqué
                if ( $request->request->has('annuler')){
                    if ($approVersement->getStatut()==ApproVersements::STAT_VALIDE
                        or $approVersement->getStatut()==ApproVersements::STAT_VALIDATION_AUTO){
                        $approVersement=$this->annulerValide($approVersement);
                        if($approVersement==false) return $this->redirectToRoute('appro_versements_ajout');
                    }
                    $approVersement->setStatut(ApproVersements::STAT_ANNULE);
                }
                //bouton "valider" cliqué
                if ( $request->request->has('valider')){
                    $approVersement=$this->valider($approVersement);
                    if($approVersement==false) return $this->redirectToRoute('appro_versements_ajout');
                }
                $this->getDoctrine()->getManager()->persist($approVersement);
                $this->getDoctrine()->getManager()->flush();
            }
            return $this->redirectToRoute('appro_versements_ajout');
        }
    }

    /**
     * @Route("/listingJournee{id}/", name="appro_versements_journee_show", methods="GET")
     */
    public function listingJournee(JourneeCaisses $journeeCaisse): Response
    {
        return $this->render('appro_versements/journee_show.html.twig', ['journeeCaisse' => $journeeCaisse]);
    }


    /**
     * @Route("/{id}", name="appro_versements_show", methods="GET")
     */
    public function show(ApproVersements $approVersement): Response
    {
        return $this->render('appro_versements/show.html.twig', ['appro_versement' => $approVersement]);
    }

    /**
     * @Route("/{id}/modifier", name="appro_versements_edit", methods="GET|POST")
     */
    public function edit(Request $request, ApproVersements $approVersement): Response
    {
        if($approVersement->getStatut()!=ApproVersements::STAT_INITIAL){
            $this->addFlash('error', 'Appro-Versement déjà validé. Impossible de le modifier');
            return $this->redirectToRoute('appro_versements_index');
        }

        $form = $this->createForm(ApproVersementsModifType::class, $approVersement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $approVersement->setUtilisateurValidateur($this->utilisateur);

            //mise à jour compta
            /*$genCompta=new GenererCompta($em);
            $transaction=$genCompta->modifComptaApproVersement($this->utilisateur,$approVersement);
            if (!$transaction){
                $this->addFlash('error', $genCompta->getErrMessage());
                return $this->redirectToRoute('appro_versements_edit');
            }*/
            $em->flush();

            return $this->redirectToRoute('appro_versements_index');
        }

        return $this->render('appro_versements/edit.html.twig', [
            'appro_versement' => $approVersement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="appro_versements_delete", methods="DELETE")
     */
    public function delete(Request $request, ApproVersements $approVersement): Response
    {
        if($approVersement->getStatut()!=ApproVersements::STAT_COMPTABILISE){
            if ($this->isCsrfTokenValid('delete'.$approVersement->getId(), $request->request->get('_token'))) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($approVersement);
                $approVersement->getJourneeCaisseSortant()->removeApproVersementSortant($approVersement);
                $approVersement->getJourneeCaisseEntrant()->removeApproVersementEntrant($approVersement);
                $em->flush();
            }
        }else{
            $this->addFlash('error','Recette depenses déjà validées. Impossible de le supprimer ! ! !');
        }

        return $this->redirectToRoute('appro_versements_ajout');
    }

    private function valider(ApproVersements $approVersement, $statut=ApproVersements::STAT_VALIDE){

        if ($statut==ApproVersements::STAT_VALIDE and $approVersement->getUtilisateur()->getId()==$this->utilisateur->getId()){
            $this->addFlash('error', 'L\'utilisateur ['.$this->utilisateur.'] est l\'initiateur de cette opération. Impossible de la valider lui même!');
            return false;
        }
        $approVersement->setStatut($statut);
        $approVersement->getJourneeCaisseEntrant()->updateM('mApproVersementEntrant', $approVersement->getMApproVersement());
        $approVersement->getJourneeCaisseSortant()->updateM('mApproVersementSortant', $approVersement->getMApproVersement());
        $approVersement->setUtilisateurValidateur($this->utilisateur);

        $genCompta=new GenererCompta($this->getDoctrine()->getManager());
        if (!$genCompta->genComptaApproVersement($this->utilisateur,$approVersement)){
            $this->addFlash('error', $genCompta->getErrMessage());
            return false;
        }

        return $approVersement;
    }

    private function annulerValide(ApproVersements $approVersement){
        if( $approVersement->getJourneeCaisseEntrant()->getStatut() != JourneeCaisses::ENCOURS){
            $this->addFlash('error', 'Annulation Impossible. Caisse Entrant ['.$approVersement->getJourneeCaisseEntrant()->getCaisse().'-'.$approVersement->getJourneeCaisseEntrant()->getUtilisateur().'] déjà fermé ! ! ! ');
            return false;
        }
        if( $approVersement->getJourneeCaisseSortant()->getStatut() != JourneeCaisses::ENCOURS){
            $this->addFlash('error', 'Annulation Impossible. Caisse Sortant ['.$approVersement->getJourneeCaisseSortant()->getCaisse().'-'.$approVersement->getJourneeCaisseSortant()->getUtilisateur().'] déjà fermé ! ! ! ');
            return false;
        }
        if ($approVersement->getStatut()==ApproVersements::STAT_VALIDE and  $approVersement->getUtilisateur()->getId()==$this->utilisateur->getId()){
            $this->addFlash('error', 'L\'utilisateur ['.$this->utilisateur.'] Impossible de valider un versement dont on initiateur !');
            return false;
        }
        $approVersement->getJourneeCaisseEntrant()->updateM('mApproVersementEntrant', -$approVersement->getMApproVersement());
        $approVersement->getJourneeCaisseSortant()->updateM('mApproVersementSortant', -$approVersement->getMApproVersement());
        $approVersement->setStatut(ApproVersements::STAT_ANNULE);
        $approVersement->setUtilisateurValidateur($this->utilisateur);

        $genCompta=new GenererCompta($this->getDoctrine()->getManager());
        if (!$genCompta->modifComptaApproVersement($this->utilisateur,$approVersement)){
            $this->addFlash('error', $genCompta->getErrMessage());
            return false;
        }
        return $approVersement;
    }
}
