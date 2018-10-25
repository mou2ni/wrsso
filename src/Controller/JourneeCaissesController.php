<?php

namespace App\Controller;

use App\Entity\Billetages;
use App\Entity\Billets;
use App\Entity\Caisses;
use App\Entity\DeviseJournees;
use App\Entity\Devises;
use App\Entity\JourneeCaisses;
use App\Entity\SystemElectInventaires;
use App\Entity\SystemElects;
use App\Entity\Utilisateurs;
use App\Form\BilletagesType;
use App\Form\DeviseJourneesType;
use App\Form\FermetureType;
use App\Form\JourneeCaissesType;
use App\Form\OuvertureFermetureType;
use App\Form\OuvertureType;
use App\Utils\GenererCompta;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/journee/caisses")
 * @ORM\Entity
 * @ORM\Table(name="journee_caisses_controller")
 */
class JourneeCaissesController extends Controller
{
    /**
     * @Route("/", name="journee_caisses_index", methods="GET")
     */
    public function index(): Response
    {
        $journeeCaisses = $this->getDoctrine()
            ->getRepository(JourneeCaisses::class)
            ->findAll();

        return $this->render('journee_caisses/index.html.twig', ['journee_caisses' => $journeeCaisses]);
    }

    /**
     * @Route("/new", name="journee_caisses_new", methods="GET|POST|UPDATE")
     */
    public function new(Request $request): Response
    {
        $em=$this->getDoctrine()->getManager();
        $journeeCaiss = new JourneeCaisses($em);
        $form = $this->createForm(JourneeCaissesType::class, $journeeCaiss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($journeeCaiss);
            $em->flush();
            return $this->redirectToRoute('journee_caisses_index');
        }

        return $this->render('journee_caisses/new.html.twig', [
            'journee_caiss' => $journeeCaiss,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/initialiser", name="journee_caisses_initialiser", methods="GET|POST|UPDATE")
     */
    public function initialiser(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateur=$this->get('session')->get('utilisateur');
        $utilisateur=$this->getDoctrine()->getRepository(Utilisateurs::class)->find($utilisateur->getId());
        //dump($utilisateur->getCompteEcartCaisse());die();
        //si l'utilisateur a choisi une caisse et cliquer sur 'initialise' retrouver ou attribuer une nouvelle journee caisse
            $caisse = $request->get('ouverture')['caisse'];

            if ($caisse){
            $caisse = $this->getDoctrine()->getRepository(Caisses::class)->find($caisse);
            $caisse->setEm($em);

            if ($caisse->getStatut()==$caisse::FERME){
                dump($caisse);die();
                $journeeCaisse = new JourneeCaisses($em);
                $journeeCaisse->setCaisse($caisse);
                $journeeCaisse->setUtilisateur($utilisateur);
                $journeeCaisse->setJourneePrecedente($caisse->getJourneeOuverte());

                $caisse->setJourneeOuverte($journeeCaisse);
                $utilisateur->setLastCaisse($caisse);
                $utilisateur->setJourneeCaisseActive($journeeCaisse);
                //dump($journeeCaisse);die();
                $em->persist($caisse);
                $em->persist($journeeCaisse);
                $em->persist($utilisateur);
                $em->flush();
                return $this->redirectToRoute('journee_caisses_ouvrir');
            }
            else{
                $this->addFlash('success', "Cette caisse est déjà occupée. Veuillez choisir une autre caisse.");
                $journeeCaisse=new JourneeCaisses($em);
            }
            }
        $form = $this->createForm(OuvertureType::class, $journeeCaisse);
        $form->handleRequest($request);
        return $this->render('journee_caisses/ouvrir.html.twig', [
            'journeePrecedente' => $journeeCaisse->getJourneePrecedente(),
            'form' => $form->createView(),
            'journeeCaisse' => $journeeCaisse,
        ]);
    }

    /**
     * @Route("/ouvrir", name="journee_caisses_ouvrir", methods="GET|POST|UPDATE")
     */
    public function ouvrir(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        //$utilisateur=$this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['login'=>'login1']);
        $utilisateur=$this->get('session')->get('utilisateur');
        $journeeCaisseActive = $this->getDoctrine()->getRepository(JourneeCaisses::class)->find($utilisateur->getJourneeCaisseActive()->getId());

        if(!$utilisateur->getEstcaissier()){
            $this->addFlash('success', "vous n'etes pas Caissier? munissez vous des droits necessaires puis reessayez");
            return $this->render('main.html.twig'
            );
        }
        elseif ($journeeCaisseActive==null) {
            return $this->redirectToRoute('journee_caisses_initialiser');
        }
        elseif($journeeCaisseActive->getStatut() == JourneeCaisses::OUVERT) {
            //$this->addFlash('success', "Vous avez une caisse ouverte");
            return $this->redirectToRoute('journee_caisses_gerer');
        }
        elseif ($journeeCaisseActive->getStatut() == JourneeCaisses::FERME){
            return $this->redirectToRoute('journee_caisses_initialiser');
        }
        elseif ($journeeCaisseActive->getStatut() == JourneeCaisses::INITIAL) {
            $journeeCaisse=$journeeCaisseActive;
            //dump($journeeCaisse);die();

        }
        //si l'utilisateur n'a pas de caisse active et n'a pas demander une initialisation (Chargement nouvelle de la page)
        if ($journeeCaisse->getCaisse()->getStatut()== Caisses::OUVERT) {
            $this->addFlash('success', "Veuillez choisir une autre caisse");
            return $this->redirectToRoute('journee_caisses_initialiser');
        }

        //else{

            /*$caisse = $utilisateur->getLastCaisse();
            $journeeCaisse = $caisse->getNouvelleJournee();
            $journeeCaisse->setUtilisateur($utilisateur);
            $journeeCaisse->setCaisse($caisse);
            $utilisateur->setJourneeCaisseActive($journeeCaisse);*/
        //}

//dump($journeeCaisse->getJourneePrecedente()->getMLiquiditeFerm());die();
        $form = $this->createForm(OuvertureType::class, $journeeCaisse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dump($form->getClickedButton()->getName());die();

            $genererCompta = new GenererCompta($this->getDoctrine()->getManager());
            $journeeCaisse->setStatut(JourneeCaisses::OUVERT);
            $em->persist($journeeCaisse);
            if (!$genererCompta->genComptaEcart($utilisateur, $journeeCaisse->getCaisse(), 'Ecart ouverture' . $journeeCaisse, $journeeCaisse->getMEcartOuv())) return $this->render('comptMainTest.html.twig', ['transactions' => [$genererCompta->getTransactions()]]);
            $em->flush();

            return $this->redirectToRoute('journee_caisses_index');
        }

        return $this->render('journee_caisses/ouvrir.html.twig', [
            'journeePrecedente' => $journeeCaisse->getJourneePrecedente(),
            'form' => $form->createView(),
            'journeeCaisse' => $journeeCaisse,
        ]);


    }

    /**
     * @Route("/enregistrer", name="journee_caisses_enregistrer", methods="GET|POST|UPDATE")
     */
    public function enregistrer(Request $request){
        //$utilisateur=$this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['login'=>'login']);
        $utilisateur=$this->get('session')->get('utilisateur');

        $em=$this->getDoctrine()->getManager();
        $operation=$request->request->get('_operation');
        $journeeCaisse=$em->getRepository('App:JourneeCaisses')->find($request->request->get('_journeeCaisse'));
        if ($operation=="OUVRIR"){

            $em = $this->getDoctrine()->getManager();
            $genererCompta = new GenererCompta($this->getDoctrine()->getManager());

            if (!$genererCompta->genComptaEcart($utilisateur, $journeeCaisse->getCaisse(), 'Ecart ouverture' . $journeeCaisse, $journeeCaisse->getMEcartOuv()))
                //return $this->render('comptMainTest.html.twig', ['transactions' => [$genererCompta->getTransactions()]]);
                dump($genererCompta); //die();
            //$journeeCaisse->set
            $journeeCaisse->setStatut(JourneeCaisses::OUVERT);
            $em->persist($journeeCaisse);

            $em->flush();

            return $this->redirectToRoute('journee_caisses_gerer');
        }
        else{
            $journeeCaisse->setStatut(JourneeCaisses::FERME);

            $newJournee = new JourneeCaisses($em);
            $newJournee->setJourneePrecedente($journeeCaisse)
                ->setCaisse($journeeCaisse->getCaisse())
                ->setUtilisateur($journeeCaisse->getUtilisateur())
                ->setMCreditDiversOuv($journeeCaisse->getMCreditDiversFerm())
                ->setMDetteDiversOuv($journeeCaisse->getMDetteDiversFerm())
                ->setMCreditDiversFerm($journeeCaisse->getMCreditDiversFerm())
                ->setMDetteDiversFerm($journeeCaisse->getMDetteDiversFerm())
                ->setMSoldeElectOuv($journeeCaisse->getMSoldeElectFerm())
                ->setMLiquiditeOuv($journeeCaisse->getMLiquiditeFerm())
                //->setMDepotClient($journeeCaisse->getMDepotClient())
                //->setMRetraitClient($journeeCaisse->getMRetraitClient())
                //->addDetteCredit($journeeCaisse->getDetteCredits())
            ;
            //dump($newJournee);die();
            foreach ($journeeCaisse->getDetteCredits() as $detteCredit){
                $newJournee->addDetteCredit($detteCredit);
            }
            //dump($newJournee);
            //die();
            foreach ($journeeCaisse->getBilletFerm()->getBilletageLignes() as $bl){
                foreach ($newJournee->getBilletFerm()->getBilletageLignes() as $newBl){
                    if ($bl->getBillet()==$newBl->getBillet()){
                        $newBl->setNbBillet($bl->getNbBillet());
                        $em->persist($newBl);
                    }
                }
            }
            foreach ($journeeCaisse->getDeviseJournees() as $dvj){
                foreach ($newJournee->getDeviseJournees() as $newdvj){
                    if ($dvj->getDevise()==$newdvj->getDevise()){
                        $newdvj->setQteOuv($dvj->getQteFerm());
                        $em->persist($newdvj);
                    }
                }
            }
            //dump($newJournee->getDeviseJournees());die();
            //foreach ($journeeCaisse->getDeviseJournee())

            $utilisateur=$newJournee->getUtilisateur();
            $em->persist($journeeCaisse);
            $em->persist($newJournee);
            $utilisateur->setJourneeCaisseActive($newJournee);
            $em->persist($utilisateur);
            $em->flush();

            return $this->redirectToRoute('journee_caisses_show', ['id'=>$journeeCaisse->getId()]);
        }



    }

    /**
     * @Route("/gerer", name="journee_caisses_gerer", methods="GET|POST|UPDATE")
     */
    public function gerer(Request $request): Response
    {
        //$utilisateur=$this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['login'=>'login']);
        $utilisateur=$this->get('session')->get('utilisateur');

        //dump($utilisateur,$utilisateur1);die();
        if(!$utilisateur->getEstcaissier()){
            $this->addFlash('success', "vous n'etes pas Caissier? munissez vous des droits necessaires puis reessayez");
            return $this->render('main.html.twig'
            );
        }
        if ($request->query->has('submit')){
            dump($request->query());die();
        }
        $journeeCaisseActive = $this->getDoctrine()->getRepository(JourneeCaisses::class)->find($utilisateur->getJourneeCaisseActive()->getId());
        //dump($journeeCaisseActive);die();
        if ($journeeCaisseActive->getStatut() == JourneeCaisses::OUVERT) {
            $journeeCaisse = $journeeCaisseActive;
            $form = $this->createForm(FermetureType::class, $journeeCaisse);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                dump($journeeCaisse);die();
            }
        return $this->render('journee_caisses/gerer.html.twig', [
            //'journeePrecedente' => $journeeCaisse->getJourneePrecedente(),
            'form' => $form->createView(),
            'journeeCaisse' => $journeeCaisse,
        ]);
        }
        else
        {
            $this->addFlash('success', "Vous n'avez pas de caisse ouverte. Veuillez l'ouvrir.");
            return $this->redirectToRoute('journee_caisses_ouvrir');
        }


    }

    /**
     * @Route("/ouverture", name="journee_caisses_ouverture", methods="GET|POST")
     */
    public function ouverture(Request $request): Response
    {

        $em = $this->getDoctrine()->getManager();
        $journeeCaisse=$this->get('session')->get('journeeCaisse');
        $user=$journeeCaisse->getUtilisateur();
        $caisse=$journeeCaisse->getCaisse();

        if (!$user->getEstCaissier()) {
            $this->addFlash('success', "vous n'etes pas Caissier? munissez vous des droits necessaires puis reessayez");
            return $this->redirectToRoute('journee_caisses_index');
        } elseif ($this->journeeCaisseEnCours($user))
        {
            $this->addFlash('success', "vous avez une caisse toutjours en cours. veuillez la fermer dabord");
            return $this->redirectToRoute('journee_caisses_index');
        }
        else{


            if(!$this->get('session')->get('electronic'))$this->get('session')->set('electronic', new SystemElectInventaires());
            $journeeCaissePrec = $this->getJourneeCaissePrec($caisse);
            $devises=$em->getRepository('App:Devises')->findAll();
            $journeeCaiss = new JourneeCaisses();
            $journeeCaiss=$this->initJournee($journeeCaiss);
            $form = $this->createForm(OuvertureType::class, $journeeCaiss);
            $form->handleRequest($request);

            if($request->request->get('billetageOuv')){
                $billets=$this->getDoctrine()->getRepository(Billets::class)->findAll();
                $this->render('billetages/ajout.html.twig', [
                    'billets' => $billets,
                    'form' => $form->createView(),
                ]);
            }
            if ($form->isSubmitted() && $form->isValid()) {
                if($form->getClickedButton()->getName()=="billetageOuv"){
                    $billetage = $this->getDoctrine()->getRepository(Billetages::class)->find(26);
                    $form1 = $this->createForm(BilletagesType::class, $billetage);
                    $form1->handleRequest($request);
                    $billets=$this->getDoctrine()->getRepository(Billets::class)->findAll();
                    return $this->render('billetages/ajout.html.twig', [
                        'billets' => $billets,
                        'form' => $form1->createView(),
                    ]);
                }
                $em = $this->getDoctrine()->getManager();
                //dump($journeeCaiss);die();
                $em->persist($journeeCaiss);
                $em->flush();

                return $this->redirectToRoute('journee_caisses_index');
            }

            return $this->render('journee_caisses/ouverture.html.twig', [
                'devises' => $devises,
                'form' => $form->createView(),
                'journeePrecedente'=>$journeeCaissePrec
            ]);
        }

    }



    /**
     * @Route("/{id}", name="journee_caisses_show", methods="GET")
     */
    public function show(JourneeCaisses $journeeCaiss): Response
    {
        return $this->render('journee_caisses/show.html.twig', ['journee_caiss' => $journeeCaiss]);
    }

    /**
     * @Route("/{id}/edit", name="journee_caisses_edit", methods="GET|POST")
     */
    public function edit(Request $request, JourneeCaisses $journeeCaiss): Response
    {
        $form = $this->createForm(JourneeCaissesType::class, $journeeCaiss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('journee_caisses_edit', ['id' => $journeeCaiss->getId()]);
        }

        return $this->render('journee_caisses/edit.html.twig', [
            'journee_caiss' => $journeeCaiss,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="journee_caisses_delete", methods="DELETE")
     */
    public function delete(Request $request, JourneeCaisses $journeeCaiss): Response
    {
        if ($this->isCsrfTokenValid('delete'.$journeeCaiss->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($journeeCaiss);
            $em->flush();
        }

        return $this->redirectToRoute('journee_caisses_index');
    }

    public function getJourneeCaissePrec(Caisses $caisse)
    {
        $journeeCaissePrec = $this->getDoctrine()
            ->getRepository(JourneeCaisses::class)
            ->findOneBy(['idCaisse'=>$caisse, 'idJourneeSuivante'=>null, 'statut'=>'F']);
        if($journeeCaissePrec)
        return $journeeCaissePrec;
        else return $journeeCaissePrec=new JourneeCaisses();
    }

    public function journeeCaisseEnCours(Utilisateurs $user){
        $em = $this->getDoctrine()->getManager();
        $journecaisse = $em->getRepository('App:JourneeCaisses')->findBy(array('utilisateur'=>$user, "statut"=>"O"));
        if ($journecaisse){

            return true;
        }
        else
            return false;
    }

    public function initJournee1(JourneeCaisses $journeeCaiss, Caisses $caisse, Utilisateurs $user)
    {
        if(!$this->get('session')->get('billetage'))
        {
            $billetage0 = $this->getDoctrine()->getRepository(Billetages::class)->find(1);
            $billetages= array($billetage0,$billetage0,$billetage0);
            $this->get('session')->set('billetage',$billetages);
        }
        $journeeCaiss->setUtilisateur($user);
        $journeeCaiss->setIdCaisse($caisse);
        $journeeCaiss->setStatut('O');
        $journeeCaissePrec=$this->getJourneeCaissePrec($caisse);
        $journeeCaiss->setMCreditDivers($journeeCaissePrec->getMCreditDivers());
        $journeeCaiss->setMDetteDivers($journeeCaissePrec->getMDetteDivers());
        if($this->get('session')->get('billetage') && $this->get('session')->get('billetage')!=$this->getDoctrine()->getRepository(Billetages::class)->find(1)){
            $journeeCaiss->setIdBilletOuv($this->get('session')->get('billetage')[0]);
            $journeeCaiss->setValeurBillet($this->get('session')->get('billetage')[0]->getValeurTotal());
            $this->getDoctrine()->getManager()->persist($this->get('session')->get('billetage')[0]);
        }
        if($this->get('session')->get('electronic')!=$this->getDoctrine()->getRepository(SystemElectInventaires::class)->find(1)){
            $journeeCaiss->setIdSystemElectInventOuv($this->get('session')->get('electronic'));
            $journeeCaiss->setSoldeElectOuv($this->get('session')->get('electronic')->getSoldeTotal());
            $this->getDoctrine()->getManager()->persist($this->get('session')->get('electronic'));
        }
        //$journeeCaiss->setSoldeElectOuv($this->get('session')->get('electronic')->getSoldeTotal());
        $journeeCaiss->setDateOuv(new \DateTime('now'));
        $journeeCaiss->setDateFerm(new \DateTime('now'));
        //$journeeCaiss->setDeviseJournee($this->contreValeurDevise($journeeCaissePrec));
        return $journeeCaiss;
    }

    public function contreValeurDevise(JourneeCaisses $journeeCaisses){
        $list=$journeeCaisses->getDeviseJournee();
        return $list;
    }

    public function initJournee(JourneeCaisses $journeeCaisses)
    {

        $journeeCaisses=$this->get('session')->get('journeeCaisse');
        if($this->get('session')->get('billetage') && $this->get('session')->get('billetage')!=$this->getDoctrine()->getRepository(Billetages::class)->find(1)){
            $journeeCaisses->setIdBilletOuv($this->get('session')->get('billetage')[0]);
            $journeeCaisses->setValeurBillet($this->get('session')->get('billetage')[0]->getValeurTotal());
            $this->getDoctrine()->getManager()->persist($this->get('session')->get('billetage')[0]);
        }
        if($this->get('session')->get('electronic')!=$this->getDoctrine()->getRepository(SystemElectInventaires::class)->find(1)){
            $journeeCaisses->setIdSystemElectInventOuv($this->get('session')->get('electronic'));
            $journeeCaisses->setSoldeElectOuv($this->get('session')->get('electronic')->getSoldeTotal());
            $this->getDoctrine()->getManager()->persist($this->get('session')->get('electronic'));
        }
        //$journeeCaiss->setSoldeElectOuv($this->get('session')->get('electronic')->getSoldeTotal());
        $journeeCaisses->setDateOuv(new \DateTime('now'));
        $journeeCaisses->setDateFerm(new \DateTime('now'));

        return $journeeCaisses;


    }

}
