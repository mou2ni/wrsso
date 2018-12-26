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
use App\Form\ChoisirCaisseType;
use App\Form\DeviseJourneesType;
use App\Form\FermetureType;
use App\Form\JourneeCaissesType;
use App\Form\OuvertureFermetureType;
use App\Form\OuvertureType;
use App\Form\UtilisateursLastCaisseType;
use App\Utils\GenererCompta;
use App\Utils\SessionUtilisateur;
use APY\DataGridBundle\Grid\GridBuilder;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Source\Source;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\ORM\Mapping as ORM;
//use APY\DataGridBundle\Grid\Source\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\VarDumper\Tests\Fixture\DumbFoo;

/**
 * @Route("/journee/caisses")
 * @ORM\Entity
 * @ORM\Table(name="journee_caisses_controller")
 */
class JourneeCaissesController extends Controller
{
    private $journeeCaisse;
    private $utilisateur;
    private $caisse;
    //private $paramComptable;

    public function __construct(SessionUtilisateur $sessionUtilisateur)
    {
        $this->utilisateur=$sessionUtilisateur->getUtilisateur();
        $this->journeeCaisse = $this->utilisateur->getJourneeCaisseActive();

    }

    /**
     * @Route("/", name="journee_caisses_index", methods="GET")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('journee_caisses_gerer');
    }

    /**
     * @Route("/gerer", name="journee_caisses_gerer", methods="GET|POST")
     */
    public function gerer(Request $request): Response
    {
        //$em=$this->getDoctrine()->getManager();

        //dernière caisse ouverte par l'utilisateur
        $this->caisse=$this->utilisateur->getLastCaisse();

        if (!$this->caisse){ // utilisateur n'ayant jamais ouvert de caisse
            return $this->redirectToRoute('journee_caisses_init');
        }

        $this->journeeCaisse=$this->caisse->getLastJournee();

        if (!$this->journeeCaisse){ //Caisse n'ayant aucune dernière journée ouverte
            //initier nouvelle journeeCaisse
            $this->journeeCaisse=$this->initJournee($this->caisse);
            //$sessionUtilisateur->setJourneeCaisse($this->journeeCaisse);
        }

        //dump($this->journeeCaisse); //die();


        switch ($this->journeeCaisse->getStatut()){
            case JourneeCaisses::CLOSE :
                //initier nouvelle journeeCaisse;
                $journeePrecedent=$this->journeeCaisse;
                $this->journeeCaisse=$this->initJournee($this->caisse,$journeePrecedent);
                return $this->render('journee_caisses/ouvrir.html.twig', ['journeeCaisse' => $this->journeeCaisse,'journeePrecedente'=>$journeePrecedent]);
                break;
            case JourneeCaisses::INITIAL :
                return $this->render('journee_caisses/ouvrir.html.twig', ['journeeCaisse' => $this->journeeCaisse,'journeePrecedente'=>$this->journeeCaisse->getJourneePrecedente()]);
                break;
            case JourneeCaisses::ENCOURS :
                if ($this->utilisateur->getId()!=$this->journeeCaisse->getUtilisateur()->getId()){ //utilisateur différent de celui de la journee caisse
                    $this->addFlash('error', 'Caisse '.$this->caisse->getCode().' déjà ouverte. Choisissez une autre caisse à ouvrir');
                    return $this->redirectToRoute('journee_caisses_init');
                    //return $this->render('journee_caisses/ouvrir.html.twig', ['journeeCaisse' => $this->journeeCaisse]);
                }
                return $this->render('journee_caisses/gerer.html.twig', ['journeeCaisse' => $this->journeeCaisse]);
                break;
            default :
                return $this->render('journee_caisses/ouvrir.html.twig', ['journeeCaisse' => $this->journeeCaisse,'journeePrecedente'=>$this->journeeCaisse->getJourneePrecedente()]);
            //return $this->redirectToRoute('journee_caisses_init');
        }






        //$utilisateur=$this->getUser();
        //dump($utilisateur);die();
        //$journeeCaisse=$utilisateur->getJourneeCaisseActive();
        //dump($journeeCaisse);die();

        //$journeeCaisse=$sessionUtilisateur->getJourneeCaisse();
        /*switch ($this->journeeCaisse->getStatut()){
            case JourneeCaisses::OUVERT : return $this->render('journee_caisses/gerer.html.twig', ['journeeCaisse' => $this->journeeCaisse,]);
            //return $this->redirectToRoute('journee_caisses_encours');
            case JourneeCaisses::INITIAL : return $this->redirectToRoute('journee_caisses_ouvrir');
            case JourneeCaisses::FERME : return $this->redirectToRoute('journee_caisses_show',['id'=>$this->journeeCaisse->getId()]);
        }*/
    }

    /**
     * @Route("/initialiser", name="journee_caisses_init", methods="GET|POST")
     */
    public function initialiser(Request $request): Response
    {
        //$em=$this->getDoctrine()->getManager();
        //$journeeCaiss = new JourneeCaisses($em);
        $form = $this->createForm(UtilisateursLastCaisseType::class, $this->utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //$this->utilisateur->getLastCaisse()->setStatut()
            $em->persist($this->utilisateur);
            $em->flush();
            return $this->redirectToRoute('journee_caisses_gerer');
        }

        return $this->render('journee_caisses/init.html.twig', [
            //'utilisateur' => $this->utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /*private function renderInitCaisse(Request $request){
        $form = $this->createForm(UtilisateursLastCaisseType::class, $this->utilisateur);
        $form->handleRequest($request);

        return $this->render('journee_caisses/init.html.twig', [
            'utilisateur' => $this->utilisateur,
            'form' => $form->createView(),
        ]);
    }*/


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


    public function initialiser1(Request $request): Response
    {

        $em = $this->getDoctrine()->getManager();
        /* recuperation de l'utisateur de la session*/
        $utilisateur = $this->utilisateur;
        //si l'utilisateur a choisi une caisse et cliquer sur 'initialiser' retrouver ou attribuer une nouvelle journee caisse
        $caisse = $request->get('choisir_caisse')['caisse'];
        //dump($caisse);die();
        if ($caisse) { //si la caisse recuperée est valide on charge de la BD cette caisse
            $caisse = $this->getDoctrine()->getRepository(Caisses::class)->find($caisse);
            $journeecaisse=$em->getRepository(JourneeCaisses::class)->findOneBy(['caisse'=>$caisse], ['id'=>'DESC'] );
            //dump($journeecaisse);die();
            if ($journeecaisse && $journeecaisse->getStatut()==JourneeCaisses::INITIAL){
                $jc=$journeecaisse;
                //dump($jc);die();
            }
            else{
                //la nouvelle journée aurra journée precedente celle qui correspond à la derniere journée ouverte sur la caisse chargée
                $journeeCaissePrec=$em->getRepository(JourneeCaisses::class)->findOneBy(['id'=>$caisse->getJourneeOuverteId()]);
                //si il n'existe pas de derniere journée ouverte sur cette caisse alors on charge une journée vide comme journée predcedente
                //dump($journeeCaissePrec);die();
                $journeeCaissePrec?:$journeeCaissePrec = new JourneeCaisses($em);
                //on initialise la nouvelle journée par la precedente
                $jc = $this->initJournee($caisse,$journeeCaissePrec);
            }
        }
        else{
            $this->addFlash('warning','veuillez choisir une caisse');
            return $this->redirectToRoute('journee_caisses_ouvrir');
        }

        //la nouvelle journée devient la journée active de l'utilisateur
        $utilisateur->setJourneeCaisseActiveId($jc->getId());
        $utilisateur->setJourneeCaisseActive($jc);
        //dump($utilisateur);die();
        $em->persist($utilisateur);
        $em->flush();

        return $this->redirectToRoute('journee_caisses_gerer');

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
        //dump($this->utilisateur);die();
        $em = $this->getDoctrine()->getManager();
        if($this->journeeCaisse->getStatut() == JourneeCaisses::ENCOURS) { //si la journée active est deja ouverte on le renvoie à la la gestion fermeture

            return $this->redirectToRoute('journee_caisses_gerer');
        }

         //creation du formulaire
        $form = $this->createForm(ChoisirCaisseType::class, $this->journeeCaisse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $genererCompta = new GenererCompta($this->getDoctrine()->getManager());
            $this->journeeCaisse->setStatut(JourneeCaisses::ENCOURS);
            $this->journeeCaisse->getCaisse()->setJourneeOuverteId($this->journeeCaisse->getId());
            $this->journeeCaisse->getCaisse()->setStatut(Caisses::OUVERT);
            $em->persist($this->journeeCaisse);
            $genererCompta->genComptaEcart($this->journeeCaisse->getUtilisateur(), $this->journeeCaisse->getCaisse(), 'Ecart ouverture' . $this->journeeCaisse, $this->journeeCaisse->getMEcartOuv());
            $em->flush();

            return $this->redirectToRoute('journee_caisses_gerer');
        }

        return $this->render('journee_caisses/ouvrir.html.twig', [
            'journeePrecedente' => $this->journeeCaisse->getJourneePrecedente(),
            'form' => $form->createView(),
            'journeeCaisse' => $this->journeeCaisse,
        ]);


    }

    /**
     * @Route("/enregistrer", name="journee_caisses_enregistrer", methods="GET|POST|UPDATE")
     */
    public function enregistrer(Request $request){
        $genererCompta=new GenererCompta($this->getDoctrine()->getManager());
        //$utilisateur = $this->get('security.token_storage')->getToken()->getUser();
        $em=$this->getDoctrine()->getManager();
        $operation=$request->request->get('_operation');
        $this->journeeCaisse=$em->getRepository('App:JourneeCaisses')->find($request->request->get('_journeeCaisse'));
        //$this->journeeCaisse=$this->caisse->getLastJournee();
        if ($operation=="OUVRIR"){
            $em = $this->getDoctrine()->getManager();
            $genererCompta->genComptaEcart($this->utilisateur, $this->caisse, 'Ecart ouverture' . $this->journeeCaisse, $this->journeeCaisse->getMEcartOuv());
            $this->journeeCaisse->setStatut(JourneeCaisses::ENCOURS);
            $this->journeeCaisse->setDateOuv(new \DateTime());
            //$this->journeeCaisse->getCaisse()->setJourneeOuverteId($this->journeeCaisse->getId());
            //$this->journeeCaisse->getCaisse()->setStatut(Caisses::OUVERT);
            $em->persist($this->journeeCaisse);

            $em->flush();

            return $this->redirectToRoute('journee_caisses_gerer');
        }
        else{
            $this->journeeCaisse->setDateFerm(new \DateTime());
            $this->journeeCaisse->setStatut(JourneeCaisses::CLOSE);
            //$this->journeeCaisse->getCaisse()->setStatut(Caisses::F);
            $this->comptabiliserFermeture($this->journeeCaisse);
            $jc = $this->initJournee($this->caisse,$this->journeeCaisse);
            //$jc->setJourneePrecedente($this->journeeCaisse);
            //$this->utilisateur->setJourneeCaisseActive($jc);
            //$this->utilisateur->setJourneeCaisseActiveId($jc->getId());
            $em->persist($jc);

            //$em->persist($this->utilisateur);
            $em->flush();

            //$genererCompta->genComptaIntercaisse($intercaisseSortant->getJourneeCaisseEntrant(),$intercaisseSortant->getJourneeCaisseEntrant()->getCaisse(),$paramComptable,$intercaisseSortant);

            return $this->redirectToRoute('journee_caisses_etat_de_caisse');
        }



    }

    /**
     * Route("/encours", name="journee_caisses_encours", methods="GET|POST|UPDATE")
     */
    public function enCours(JourneeCaisses $journeeCaisse): Response
    {
        //$utilisateur=$this->get('security.token_storage')->getToken()->getUser();
        /*if(!$utilisateur->getEstcaissier()){
            $this->addFlash('success', "vous n'etes pas Caissier? munissez vous des droits necessaires puis reessayez");
            return $this->render('main.html.twig'
            );
        }*/
        //$journeeCaisseActive = $this->getDoctrine()->getRepository(JourneeCaisses::class)->find($utilisateur->getJourneeCaisseActiveId());
        //$journeeCaisseActive=$utilisateur->getJourneeCaisseActive();
        return $this->render('journee_caisses/gerer.html.twig', ['journeeCaisse' => $journeeCaisse,]);
        //dump($journeeCaisseActive); die();
        /*if ($journeeCaisseActive->getStatut() == JourneeCaisses::OUVERT) {
            //dump($journeeCaisseActive);die();
            return $this->render('journee_caisses/gerer.html.twig', ['journeeCaisse' => $journeeCaisseActive,]);
        }
        else
        {
            $this->addFlash('success', "Vous n'avez pas de caisse ouverte. Veuillez l'ouvrir.");
            return $this->redirectToRoute('journee_caisses_ouvrir');
        }*/


    }

    /**
     * @Route("/etat/caisse", name="journee_caisses_etat_de_caisse", methods="GET|POST|UPDATE")
     */
    public function etatDeCaisse(Request $request): Response
    {
        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('caisse',EntityType::class, [
                'class' => Caisses::class
            ])
            //->add('dateDeb')
            //->add('dateFin')
            ->getForm();
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $utilisateur = $this->get('security.token_storage')->getToken()->getUser();
        $jc = $em->getRepository(JourneeCaisses::class)->findOneById($utilisateur->getJourneeCaisseActiveId());
        $caisse = $jc->getCaisse();
        //dump($caisse);die();
        $dateDeb = new \DateTime("01-11-2018");
        $dateFin = new \DateTime('now');
        if ($request->get('dateDeb'))
            $dateDeb = new \DateTime($request->get('dateDeb'));
        if ($request->get('dateFin'))
            $dateFin = new \DateTime($request->get('dateFin'));
            if ( $form->isSubmitted())$caisse = $form['caisse']->getData();
        //dump($request->get('form_caisse'));die();
        $journeeCaisses = $this->getDoctrine()
            ->getRepository(JourneeCaisses::class)
            ->getJourneesDeCaisse($caisse, $dateDeb, $dateFin);


        if ($form->isSubmitted()){

            //dump($request);die();
        }

        return $this->render('journee_caisses/etat_de_caisse.html.twig', [
            'journee_caisses' => $journeeCaisses,
            'form' => $form->createView()]);

    }

    /**
     * @Route("/etat/compense", name="journee_caisses_compense", methods="GET|POST|UPDATE")
     */
    public function etatCompense(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $date = new \DateTime('now');
        $date->setDate(2018, 11, 6);
        $utilisateur = $this->get('security.token_storage')->getToken()->getUser();
        if ($request->get('date'))
        $date = new \DateTime($request->get('date'));
        $journeeCaisses = $em->getRepository(JourneeCaisses::class)->getJourneeCaissesDuJour($date);

        $ecart=0;
        $cvd=0;
        $intercaisse=0;
        $mouvementFond=0;
        $credit=0;
        $dette=0;
        $depot=0;
        $retrait=0;
        $liquidite=0;
        $elect=0;
        $disponibilite=0;
        $netFermeture=0;
        $netOuverture=0;
        $compense=0;
        $caisses='';
        foreach ($journeeCaisses as $journeeCaisse){
            /*foreach ($journeeCaisse->getDeviseJournee() as $deviseJournee){
                dump($deviseJournee->getDevise().' '.$deviseJournee->getBilletOuv());
            }
            dump('------------------');
            */
            $ecart += $journeeCaisse->getMEcartFerm();
            $cvd += $journeeCaisse->getMCvd();
            $intercaisse += $journeeCaisse->getMIntercaisses();
            $mouvementFond += $journeeCaisse->getMouvementFond();
            $credit+=$journeeCaisse->getMCreditDiversFerm();
            $dette+=$journeeCaisse->getMDetteDiversFerm();
            $depot+=$journeeCaisse->getMDepotClient();
            $retrait+=$journeeCaisse->getMRetraitClient();
            $liquidite+=$journeeCaisse->getMLiquiditeFerm();
            $elect+=$journeeCaisse->getMSoldeElectFerm();
            $disponibilite += $journeeCaisse->getDisponibiliteFerm();
            $netOuverture += $journeeCaisse->getSoldeNetOuv();
            $netFermeture += $journeeCaisse->getSoldeNetFerm();
            //$journeeCaisse=new JourneeCaisses();
            $compense += $journeeCaisse->getCompense();
            $caisses = $caisses.', '.$journeeCaisse->getCaisse();
        }//die();
        $journeeCaisseRecap = array('ecart'=>$ecart, 'cvd'=>$cvd, 'intercaisse'=>$intercaisse,
            'mouvement'=>$mouvementFond, 'credit'=>$credit, 'dette'=>$dette, 'depot'=>$depot,
            'retrait'=>$retrait, 'liquidite'=>$liquidite, 'disponibilite'=>$disponibilite,
            'netOuv'=>$netOuverture, 'netFerm'=>$netFermeture, 'compense'=>$compense,
            'date'=>$date, 'caisse'=>$caisses);
        /*dump($journeeCaisseRecap);
        dump($cvd);
        dump($intercaisse);
        dump($liquidite);
        dump($elect);
        die();*/

        return $this->render('journee_caisses/etat_compense.html.twig'
            , ['journee_caisses' => $journeeCaisses,
                'journee_caisses_recap' => $journeeCaisseRecap]
            );

    }



    /**
     * @param Source|null $source
     * @param array $options
     * @return GridBuilder
     */
    public function createGridBuilder(Source $source = null, array $options = [])
    {
        return $this->container->get('apy_grid.factory')->createBuilder('grid', $source, $options);
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
     * @Route("/{id}", name="journee_caisses_show", methods="GET|UPDATE")
     */
    public function show(JourneeCaisses $journeeCaiss): Response
    {
        $form = $this->createForm(FermetureType::class, $journeeCaiss);
        //$form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            dump($journeeCaiss);die();
        }
        return $this->render('journee_caisses/gerer.html.twig', [
            'form' => $form->createView(),
            'journeeCaisse' => $journeeCaiss,
        ]);
        //return $this->render('journee_caisses/gerer.html.twig', ['journee_caiss' => $journeeCaiss]);
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


    public function comptabiliserFermeture(JourneeCaisses $journeeCaisse){
        $paramComptable=$this->getDoctrine()->getRepository(ParamComptables::class)->findOneBy(['codeStructure'=>'YESBO']);
        $genererCompta=new GenererCompta($this->getDoctrine()->getManager());
        $genererCompta->genComptaIntercaisse($journeeCaisse->getUtilisateur(),$journeeCaisse->getCaisse(),$paramComptable, $journeeCaisse->getMIntercaisses());
        $genererCompta->genComptaFermeture($journeeCaisse->getUtilisateur(),$journeeCaisse->getCaisse(),$paramComptable,$journeeCaisse->getMIntercaisses(),$journeeCaisse->getMReceptionTrans() - $journeeCaisse->getMEmissionTrans(),$journeeCaisse->getMCvd(),$journeeCaisse->getMEcartFerm());
        $genererCompta->genComptaCompense($journeeCaisse->getUtilisateur(),$journeeCaisse->getCaisse(),$paramComptable,$journeeCaisse->getMReceptionTrans() - $journeeCaisse->getMEmissionTrans());
        $genererCompta->genComptaCvDevise($journeeCaisse->getUtilisateur(),$journeeCaisse->getCaisse(),$journeeCaisse->getMCvd());
        $genererCompta->genComptaEcart($journeeCaisse->getUtilisateur(),$journeeCaisse->getCaisse(), 'Ecart Fermeture'. $journeeCaisse, $journeeCaisse->getMEcartFerm());
    }

    private function initJournee(Caisses $caisse, JourneeCaisses $journeeCaissePrecedent=null)
    {
        $em = $this->getDoctrine()->getManager();
        //$user = $this->get('security.token_storage')->getToken()->getUser();
        //$user = $em->getRepository(Utilisateurs::class)->findOneBy(['login'=>'login1']);
        //$user->setJourneeCaisseActiveId()

        $newJournee = new JourneeCaisses($em);
        $newJournee->setCaisse($caisse)
            ->setUtilisateur($this->utilisateur)
            ->setStatut(JourneeCaisses::INITIAL);

        if (!$journeeCaissePrecedent) { //initialiser à partenir de néant !!!
            return $newJournee;
        }
        //$caisse->getJourneeOuverte()->setDateFerm(new \DateTime());
        //$caisse->getJourneeOuverte()->setStatut(JourneeCaisses::FERME);
        //$user->setJourneeCaisseActive($newJournee);
        //$em->persist($user);
        $newJournee->setJourneePrecedente($journeeCaissePrecedent)
        //->setCaisse($caisse)
        //    ->setUtilisateur($user)
            ->setMCreditDiversOuv($journeeCaissePrecedent->getMCreditDiversFerm())
            ->setMDetteDiversOuv($journeeCaissePrecedent->getMDetteDiversFerm())
            ->setMCreditDiversFerm($journeeCaissePrecedent->getMCreditDiversFerm())
            ->setMDetteDiversFerm($journeeCaissePrecedent->getMDetteDiversFerm())
            ->setMSoldeElectOuv($journeeCaissePrecedent->getMSoldeElectFerm())
            ->setMLiquiditeOuv($journeeCaissePrecedent->getMLiquiditeFerm())
            //->getUtilisateur()->setJourneeCaisseActive($newJournee)
        ;

        foreach ($journeeCaissePrecedent->getDetteCredits() as $detteCredit){
            $newJournee->addDetteCredit($detteCredit);
        }


        foreach ($journeeCaissePrecedent->getBilletFerm()->getBilletageLignes() as $bl){
            $newBl = new BilletageLignes();
            $newBl->setNbBillet($bl->getNbBillet());
            $newBl->setValeurBillet($bl->getValeurBillet());
            $newBl->setBillet($bl->getBillet());
            $newJournee->getBilletOuv()->addBilletageLigne($newBl);
            $em->persist($newBl);
        }


        foreach ($journeeCaissePrecedent->getDeviseJournees() as $dvj){
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
                $deviseJournee = new DeviseJournees($journeeCaissePrecedent,$devise);
                $newJournee->addDeviseJournee($deviseJournee);
                $em->persist($deviseJournee);
            }

        }
        //dump($newJournee->getDeviseJournee()); die();



        //$caisse->setJourneeOuverte($newJournee);
        foreach ($journeeCaissePrecedent->getSystemElectInventFerm()->getSystemElectLigneInventaires() as $seli){
            $newSeli = new SystemElectLigneInventaires();
            $newSeli->setSolde($seli->getSolde());
            $newSeli->setIdSystemElect($seli->getIdSystemElect());
            //$newSeli->getIdSystemElect()
            $newJournee->getSystemElectInventOuv()->addSystemElectLigneInventaires($newSeli);
        }

        //dump($journeeCaisse->getSystemElectInventFerm()->getSystemElectLigneInventaires());die();
        //$newJournee->getCaisse()->setJourneeOuverteId($newJournee->getId());

        //$newJournee->getUtilisateur()->setLastCaisse($newJournee->getCaisse());

        $em->persist($newJournee);
        $em->persist($caisse);
        $em->flush();

        return $newJournee;

    }


}
