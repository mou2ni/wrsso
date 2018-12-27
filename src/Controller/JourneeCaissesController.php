<?php

namespace App\Controller;

use App\Entity\BilletageLignes;
use App\Entity\Billetages;
use App\Entity\Billets;
use App\Entity\Caisses;
use App\Entity\Comptes;
use App\Entity\DetteCreditDivers;
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
use App\Repository\DetteCreditDiversRepository;
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
        //dernière caisse ouverte par l'utilisateur ou null si inexistant
        $this->caisse=$sessionUtilisateur->getLastCaisse();
        //dernière journée de la caisse ou null si inexistant
        $this->journeeCaisse=$sessionUtilisateur->getJourneeCaisse();


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
        //$this->caisse=$this->utilisateur->getLastCaisse();

        if (!$this->caisse){ // utilisateur n'ayant jamais ouvert de caisse
            return $this->redirectToRoute('journee_caisses_init');
        }

        $this->journeeCaisse=$this->caisse->getLastJournee();

        if (!$this->journeeCaisse){ //Caisse n'ayant aucune dernière journée ouverte
            //initier nouvelle journeeCaisse
            $this->journeeCaisse=$this->initJournee($this->caisse);
        }

        switch ($this->journeeCaisse->getStatut()){
            case JourneeCaisses::CLOSE :
                //initier nouvelle journeeCaisse;
                $journeePrecedent=$this->journeeCaisse;
                $this->journeeCaisse=$this->initJournee($this->caisse,$journeePrecedent);
                return $this->render('journee_caisses/ouvrir.html.twig', ['journeeCaisse' => $this->journeeCaisse,'journeePrecedente'=>$journeePrecedent]);
                break;
            case JourneeCaisses::INITIAL :
                //dump($this->journeeCaisse->getBilletOuv());die();
                return $this->render('journee_caisses/ouvrir.html.twig', ['journeeCaisse' => $this->journeeCaisse,'journeePrecedente'=>$this->journeeCaisse->getJourneePrecedente()]);
                break;
            case JourneeCaisses::ENCOURS :
                if ($this->utilisateur->getId()!=$this->journeeCaisse->getUtilisateur()->getId()){ //utilisateur différent de celui de la journee caisse
                    $this->addFlash('error', 'Caisse '.$this->caisse->getCode().' déjà ouverte. Choisissez une autre caisse à ouvrir');
                    return $this->redirectToRoute('journee_caisses_init');
                }
                return $this->render('journee_caisses/gerer.html.twig', ['journeeCaisse' => $this->journeeCaisse]);
                break;
            default :
                return $this->render('journee_caisses/ouvrir.html.twig', ['journeeCaisse' => $this->journeeCaisse,'journeePrecedente'=>$this->journeeCaisse->getJourneePrecedente()]);
        }
    }

    /**
     * @Route("/changer", name="journee_caisses_init", methods="GET|POST")
     */
    public function changerCaisse(Request $request): Response
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

    /**
     * @Route("/enregistrer", name="journee_caisses_enregistrer", methods="GET|POST|UPDATE")
     */
    public function enregistrer(Request $request){
        if (!$this->journeeCaisse){
            $this->addFlash('error', 'ERREUR D\'ENREGISTREMENT RENCONTREE. RECOMMENCEZ !!!');
            return $this->redirectToRoute('journee_caisses_gerer');
        }
        $em=$this->getDoctrine()->getManager();
        $genererCompta=new GenererCompta($em);
        //$utilisateur = $this->get('security.token_storage')->getToken()->getUser();
        $operation=$request->request->get('_operation');
        //$this->journeeCaisse=$em->getRepository('App:JourneeCaisses')->find($request->request->get('_journeeCaisse'));
        //$this->journeeCaisse=$this->caisse->getLastJournee();
        if ($operation=="OUVRIR"){
            $genererCompta->genComptaEcart($this->utilisateur, $this->caisse, 'Ecart ouverture' . $this->journeeCaisse, $this->journeeCaisse->getMEcartOuv());
            $this->journeeCaisse->setStatut(JourneeCaisses::ENCOURS);
            $this->journeeCaisse->setDateOuv(new \DateTime());
            $this->setSoldeFerm();
            //$this->journeeCaisse->getCaisse()->setJourneeOuverteId($this->journeeCaisse->getId());
            //$this->journeeCaisse->getCaisse()->setStatut(Caisses::OUVERT);
            $em->persist($this->journeeCaisse);

            $em->flush();

            return $this->redirectToRoute('journee_caisses_gerer');
        }
        else{
            $this->journeeCaisse->setDateFerm(new \DateTime());
            $this->journeeCaisse->setStatut(JourneeCaisses::CLOSE);
            $this->comptabiliserFermeture($this->journeeCaisse);
            $jc = $this->initJournee($this->caisse,$this->journeeCaisse);

            $em->persist($this->journeeCaisse);
            $em->persist($jc);
            $em->flush();

            return $this->redirectToRoute('journee_caisses_index');
        }
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
        //$utilisateur = $this->get('security.token_storage')->getToken()->getUser();
        //$jc = $em->getRepository(JourneeCaisses::class)->findOneById($this->utilisateur->getJourneeCaisseActive());
        $caisse = $this->caisse;
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
            'journeeCaisse' => $this->journeeCaisse,
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
            $em->persist($newJournee);
            return $newJournee;
        }
         $newJournee->setJourneePrecedente($journeeCaissePrecedent)
            ->setMCreditDiversOuv($journeeCaissePrecedent->getMCreditDiversFerm())
            ->setMDetteDiversOuv($journeeCaissePrecedent->getMDetteDiversFerm())
            ->setMCreditDiversFerm($journeeCaissePrecedent->getMCreditDiversFerm())
            ->setMDetteDiversFerm($journeeCaissePrecedent->getMDetteDiversFerm())
            ->setMSoldeElectOuv($journeeCaissePrecedent->getMSoldeElectFerm())
            ->setMLiquiditeOuv($journeeCaissePrecedent->getMLiquiditeFerm())
        ;



        foreach ( $journeeCaissePrecedent->getDetteCredits() as $detteCredit) {
            if ($detteCredit->getStatut()== DetteCreditDivers::CREDIT_EN_COUR or $detteCredit->getStatut()== DetteCreditDivers::DETTE_EN_COUR){
                $detteCredit->setJourneeCaisseActive($newJournee);
                $em->persist($detteCredit);
            }
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

        foreach ($journeeCaissePrecedent->getSystemElectInventFerm()->getSystemElectLigneInventaires() as $seli){
            $newSeli = new SystemElectLigneInventaires();
            $newSeli->setSolde($seli->getSolde());
            $newSeli->setIdSystemElect($seli->getIdSystemElect());
            //$newSeli->getIdSystemElect()
            $newJournee->getSystemElectInventOuv()->addSystemElectLigneInventaires($newSeli);
        }
        $em->persist($newJournee);
        //$em->persist($caisse);
        //$em->flush();
        return $newJournee;
    }

    private function setSoldeFerm(){

        //maj solde fermeture avec les données d'ouverture
        foreach ($this->journeeCaisse->getBilletOuv()->getBilletageLignes() as $billetageLigneOuv){
            $newLigne=new BilletageLignes();
            $newLigne->setBillet($billetageLigneOuv->getBillet())
            ->setNbBillet($billetageLigneOuv->getNbBillet())
            ->setValeurBillet($billetageLigneOuv->getValeurBillet())
            ->setBilletages($this->journeeCaisse->getBilletFerm());
            $this->getDoctrine()->getManager()->persist($newLigne);
        }
        $this->journeeCaisse->setMLiquiditeFerm($this->journeeCaisse->getMLiquiditeOuv());

        //soldes électroniques
        foreach ($this->journeeCaisse->getSystemElectInventOuv()->getSystemElectLigneInventaires() as $seli){
            $newSeli=new SystemElectLigneInventaires();
            $newSeli->setSolde($seli->getSolde())
                ->setIdSystemElect($seli->getIdSystemElect())
                ->setIdSystemElectInventaire($seli->getIdSystemElectInventaire());
            $this->getDoctrine()->getManager()->persist($newSeli);
        }
        $this->journeeCaisse->setMSoldeElectFerm($this->journeeCaisse->getMSoldeElectOuv());

    }
}
