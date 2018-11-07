<?php

namespace App\Controller;

use App\Entity\BilletageLignes;
use App\Entity\Billetages;
use App\Entity\Billets;
use App\Entity\Caisses;
use App\Entity\Comptes;
use App\Entity\DeviseJournees;
use App\Entity\Devises;
use App\Entity\JourneeCaisses;
use App\Entity\ParamComptables;
use App\Entity\SystemElectInventaires;
use App\Entity\SystemElectLigneInventaires;
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
use Symfony\Component\Validator\Constraints\DateTime;

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
        /* recuperation de l'utisateur de la session*/
        $utilisateur = $this->get('security.token_storage')->getToken()->getUser();
        //si l'utilisateur a choisi une caisse et cliquer sur 'initialiser' retrouver ou attribuer une nouvelle journee caisse
        $caisse = $request->get('ouverture')['caisse'];
        if ($caisse) { //si la caisse recuperée est valide on charge de la BD cette caisse
            $caisse = $this->getDoctrine()->getRepository(Caisses::class)->find($caisse);
            $journeecaisse=$em->getRepository(JourneeCaisses::class)->findOneBy(['caisse'=>$caisse], ['id'=>'DESC'] );
            //dump($journeecaisse);die();
            if ($journeecaisse->getStatut()==JourneeCaisses::INITIAL){
                $jc=$journeecaisse;
            }
            else{
                //la nouvelle journée aurra journée precedente celle qui correspond à la derniere journée ouverte sur la caisse chargée
                $journeeCaissePrec=$em->getRepository(JourneeCaisses::class)->findOneBy(['id'=>$caisse->getJourneeOuverteId()]);
                //si il n'existe pas de derniere journée ouverte sur cette caisse alors on charge une journée vide comme journée predcedente
                $journeeCaissePrec?:$journeeCaissePrec = new JourneeCaisses($em);
                //on initialise la nouvelle journée par la precedente
                $jc = $this->initJournee($journeeCaissePrec,$caisse);
            }
        }
        else{
            $this->addFlash('warning','veuillez choisir une caisse');
            return $this->redirectToRoute('journee_caisses_ouvrir');
        }
        /*else{ //sinon on charge la derniere caisse de l'utilisateur
            $caisse=$utilisateur->getLastCaisse();
        }*/
        //dump($jc);die();
        //la nouvelle journée devient la journée active de l'utilisateur
        $utilisateur->setJourneeCaisseActiveId($jc->getId());
        $em->persist($utilisateur);
        $em->flush();

        return $this->redirectToRoute('journee_caisses_ouvrir');

        /*$journeeCaisse = new JourneeCaisses($em);
        $em->persist($journeeCaisse);
        //dump($journeeCaisse);die();
        $form = $this->createForm(OuvertureType::class, $journeeCaisse);
        $form->handleRequest($request);
        return $this->render('journee_caisses/ouvrir.html.twig', [
            'journeePrecedente' => $journeeCaisse->getJourneePrecedente(),
            'form' => $form->createView(),
            'journeeCaisse' => $journeeCaisse,
        ]);*/
    }

    /**
     * @Route("/ouvrir", name="journee_caisses_ouvrir", methods="GET|POST|UPDATE")
     */
    public function ouvrir(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        /* recuperation de l'utisateur de la session*/
        $utilisateur = $this->get('security.token_storage')->getToken()->getUser();
        //recuperation de la journée active de l'utilisateur
        $journeeCaisseActive=$this->getDoctrine()->getRepository(JourneeCaisses::class)->findOneBy(['id'=>$utilisateur->getJourneeCaisseActiveId()]);

        if(!$utilisateur->getEstcaissier()){ //si l'utilsateur n'est pas un caissier il est renvoyé vers une autre page
            $this->addFlash('success', "vous n'etes pas Caissier? munissez vous des droits necessaires puis reessayez");
            return $this->render('main.html.twig'
            );
        }

        elseif ($journeeCaisseActive) { // si il est caisseir mais n'a pas de journée active on le revoie chercher une caisse à initialiser

        if($journeeCaisseActive->getStatut() == JourneeCaisses::OUVERT) { //si la journée active est deja ouverte on le renvoie à la la gestion fermeture

            return $this->redirectToRoute('journee_caisses_gerer');
        }
        /*elseif ($journeeCaisseActive->getStatut() == JourneeCaisses::FERME){ // si la journée active est fermée on le renvoie à initialiser
            return $this->redirectToRoute('journee_caisses_initialiser');
        }*/
        else { //if ($journeeCaisseActive->getStatut() == JourneeCaisses::INITIAL) { //si la journée active est à initial on l'affiche la journée pour ouverture
            $journeeCaisse=$journeeCaisseActive;

        }
        /*if ($journeeCaisse->getCaisse()->getStatut()== Caisses::OUVERT) {
            $this->addFlash('success', "Veuillez choisir une autre caisse");
        }*/
}
            //creation du formulaire
        $form = $this->createForm(OuvertureType::class, $journeeCaisse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $genererCompta = new GenererCompta($this->getDoctrine()->getManager());
            $journeeCaisse->setStatut(JourneeCaisses::OUVERT);
            //dump($journeeCaisse->getId()); die();
            $journeeCaisse->getCaisse()->setJourneeOuverteId($journeeCaisse->getId());
            $journeeCaisse->getCaisse()->setStatut(Caisses::OUVERT);
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
        $genererCompta=new GenererCompta($this->getDoctrine()->getManager());
        $utilisateur = $this->get('security.token_storage')->getToken()->getUser();
        $em=$this->getDoctrine()->getManager();
        $operation=$request->request->get('_operation');
        $journeeCaisse=$em->getRepository('App:JourneeCaisses')->find($request->request->get('_journeeCaisse'));
        if ($operation=="OUVRIR"){
            $em = $this->getDoctrine()->getManager();
            if (!$genererCompta->genComptaEcart($utilisateur, $journeeCaisse->getCaisse(), 'Ecart ouverture' . $journeeCaisse, $journeeCaisse->getMEcartOuv()))
                dump($genererCompta); //die();
            $journeeCaisse->setStatut(JourneeCaisses::OUVERT);
            $journeeCaisse->getCaisse()->setJourneeOuverteId($journeeCaisse->getId());
            $journeeCaisse->getCaisse()->setStatut(Caisses::OUVERT);
            $em->persist($journeeCaisse);

            $em->flush();

            return $this->redirectToRoute('journee_caisses_gerer');
        }
        else{
            $journeeCaisse->setDateFerm(new \DateTime());
            $journeeCaisse->setStatut(JourneeCaisses::FERME);
            $journeeCaisse->getCaisse()->setStatut(Caisses::FERME);
            $this->comptabiliserFermeture($journeeCaisse);
            $jc = $this->initJournee($journeeCaisse, $journeeCaisse->getCaisse());
            $jc->setJourneePrecedente($journeeCaisse);
            $utilisateur->setJourneeCaisseActiveId($jc->getId());
            $em->persist($jc);

            $em->persist($utilisateur);
            $em->flush();

            //$genererCompta->genComptaIntercaisse($intercaisseSortant->getJourneeCaisseEntrant(),$intercaisseSortant->getJourneeCaisseEntrant()->getCaisse(),$paramComptable,$intercaisseSortant);

            return $this->redirectToRoute('journee_caisses_ouvrir');
        }



    }

    /**
     * @Route("/gerer", name="journee_caisses_gerer", methods="GET|POST|UPDATE")
     */
    public function gerer(Request $request): Response
    {
        $utilisateur = $this->get('security.token_storage')->getToken()->getUser();
        if(!$utilisateur->getEstcaissier()){
            $this->addFlash('success', "vous n'etes pas Caissier? munissez vous des droits necessaires puis reessayez");
            return $this->render('main.html.twig'
            );
        }
        if ($request->query->has('submit')){
            dump($request->query());die();
        }
        $journeeCaisseActive = $this->getDoctrine()->getRepository(JourneeCaisses::class)->find($utilisateur->getJourneeCaisseActiveId());
        //dump($journeeCaisseActive); die();
        if ($journeeCaisseActive->getStatut() == JourneeCaisses::OUVERT) {
            $journeeCaisse = $journeeCaisseActive;
            //dump($journeeCaisse);die();
            $form = $this->createForm(FermetureType::class, $journeeCaisse);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                dump($journeeCaisse);die();
            }
            return $this->render('journee_caisses/gerer.html.twig', [
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
     * @Route("/etat/caisse", name="journee_caisses_etat_de_caisse", methods="GET")
     */
    public function etatDeCaisse(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateur = $this->get('security.token_storage')->getToken()->getUser();
        $jc = $em->getRepository(JourneeCaisses::class)->findOneById($utilisateur->getJourneeCaisseActiveId());
        $caisse = $jc->getCaisse();
        $journeeCaisses = $this->getDoctrine()
            ->getRepository(JourneeCaisses::class)
            ->findBy(['caisse'=>$caisse]);

        return $this->render('journee_caisses/etat_de_caisse.html.twig', ['journee_caisses' => $journeeCaisses]);
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
        $em = $this->getDoctrine()->getManager();
        $journeeCaissePrec = $this->getDoctrine()
            ->getRepository(JourneeCaisses::class)
            ->findOneBy(['idCaisse'=>$caisse, 'idJourneeSuivante'=>null, 'statut'=>'F']);
        if($journeeCaissePrec)
            return $journeeCaissePrec;
        else return $journeeCaissePrec=new JourneeCaisses($em);
    }

    public function journeeCaisseEnCours(Utilisateurs $user){
        $em = $this->getDoctrine()->getManager();
        $journecaisse = $em->getRepository('App:JourneeCaisses')->findBy(array('utilisateur'=>$user, "statut"=>"O"));
        if ($journecaisse){

            return $journecaisse;
        }
        else
            return false;
    }

    public function contreValeurDevise(JourneeCaisses $journeeCaisses){
        $list=$journeeCaisses->getDeviseJournee();
        return $list;
    }

    public function initJournee1(JourneeCaisses $journeeCaisses)
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

    public function initJournee(JourneeCaisses $journeeCaisse, Caisses $caisse)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        //$user = $em->getRepository(Utilisateurs::class)->findOneBy(['login'=>'login1']);
        //$user->setJourneeCaisseActiveId()

        $newJournee = new JourneeCaisses($em);
        //$caisse->getJourneeOuverte()->setDateFerm(new \DateTime());
        //$caisse->getJourneeOuverte()->setStatut(JourneeCaisses::FERME);
        //$user->setJourneeCaisseActive($newJournee);
        //$em->persist($user);
        $newJournee//->setJourneePrecedente($journeeCaisse)
        ->setCaisse($caisse)
            ->setUtilisateur($user)
            ->setMCreditDiversOuv($journeeCaisse->getMCreditDiversFerm())
            ->setMDetteDiversOuv($journeeCaisse->getMDetteDiversFerm())
            ->setMCreditDiversFerm($journeeCaisse->getMCreditDiversFerm())
            ->setMDetteDiversFerm($journeeCaisse->getMDetteDiversFerm())
            ->setMSoldeElectOuv($journeeCaisse->getMSoldeElectFerm())
            ->setMLiquiditeOuv($journeeCaisse->getMLiquiditeFerm())
            ->setStatut(JourneeCaisses::INITIAL)
            //->getUtilisateur()->setJourneeCaisseActive($newJournee)
        ;

        foreach ($journeeCaisse->getDetteCredits() as $detteCredit){
            $newJournee->addDetteCredit($detteCredit);
        }


        foreach ($journeeCaisse->getBilletFerm()->getBilletageLignes() as $bl){
            $newBl = new BilletageLignes();
            $newBl->setNbBillet($bl->getNbBillet());
            $newBl->setValeurBillet($bl->getValeurBillet());
            $newBl->setBillet($bl->getBillet());
            $newJournee->getBilletOuv()->addBilletageLigne($newBl);
            $em->persist($newBl);
        }


        foreach ($journeeCaisse->getDeviseJournees() as $dvj){
            //foreach ($newJournee->getDeviseJournees() as $newdvj){
            //  if ($dvj->getDevise()==$newdvj->getDevise()){
            //$newJournee->getDeviseJournee()
            $newdvj = new DeviseJournees($newJournee, $dvj->getDevise());
            $newdvj->setQteOuv($dvj->getQteFerm());
            foreach ($dvj->getBilletFerm()->getBilletageLignes() as $bl){
                $newBl = new BilletageLignes();
                $newBl->setNbBillet($bl->getNbBillet());
                $newBl->setValeurBillet($bl->getValeurBillet());
                $newBl->setBillet($bl->getBillet());
                $newdvj->getBilletOuv()->addBilletageLigne($newBl);
                $em->persist($newBl);
            }
            $newJournee->addDeviseJournee($newdvj);
            $em->persist($newdvj);
            //}
            //}
        }
        if ($newJournee->getDeviseJournee()->isEmpty()){
            $devises = $em->getRepository(Devises::class)->findAll();
            foreach ($devises as $devise){
                $deviseJournee = new DeviseJournees($journeeCaisse,$devise);
                $newJournee->addDeviseJournee($deviseJournee);
                $em->persist($deviseJournee);
            }

        }
        //dump($newJournee->getDeviseJournee()); die();



        //$caisse->setJourneeOuverte($newJournee);
        foreach ($journeeCaisse->getSystemElectInventFerm()->getSystemElectLigneInventaires() as $seli){
            $newSeli = new SystemElectLigneInventaires();
            $newSeli->setSolde($seli->getSolde());
            $newSeli->setIdSystemElect($seli->getIdSystemElect());
            //$newSeli->getIdSystemElect()
            $newJournee->getSystemElectInventOuv()->addSystemElectLigneInventaires($newSeli);
        }

        //dump($journeeCaisse->getSystemElectInventFerm()->getSystemElectLigneInventaires());die();
        //$newJournee->getCaisse()->setJourneeOuverteId($newJournee->getId());

        $newJournee->getUtilisateur()->setLastCaisse($newJournee->getCaisse());

        $em->persist($newJournee);
        $em->persist($caisse);
        $em->flush();

        return $newJournee;

    }

    public function comptabiliserFermeture(JourneeCaisses $journeeCaisse){
        $paramComptable=$this->getDoctrine()->getRepository(ParamComptables::class)->findOneBy(['codeStructure'=>'YESBO']);
        $genererCompta=new GenererCompta($this->getDoctrine()->getManager());
        $genererCompta->genComptaIntercaisse($journeeCaisse->getUtilisateur(),$journeeCaisse->getCaisse(),$paramComptable, $journeeCaisse->getMIntercaisses());
        $genererCompta->genComptaFermeture($journeeCaisse->getUtilisateur(),$journeeCaisse->getCaisse(),$paramComptable,$journeeCaisse->getMIntercaisses(),$journeeCaisse->getMReceptionTrans() - $journeeCaisse->getMEmissionTrans(),$journeeCaisse->getMCvd(),$journeeCaisse->getMEcartFerm());
        $genererCompta->genComptaCompense($journeeCaisse->getUtilisateur(),$journeeCaisse->getCaisse(),$paramComptable,$journeeCaisse->getMReceptionTrans() - $journeeCaisse->getMEmissionTrans());
        $genererCompta->genComptaCvDevise($journeeCaisse->getUtilisateur(),$journeeCaisse->getCaisse(),$journeeCaisse->getMCvd());
        $genererCompta->genComptaEcart($journeeCaisse->getUtilisateur(),$journeeCaisse->getCaisse(), 'Ecart Fermeture'. $journeeCaisse, $journeeCaisse->getMEcartFerm());
    }

}
