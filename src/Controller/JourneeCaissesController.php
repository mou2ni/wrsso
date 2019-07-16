<?php

namespace App\Controller;

use App\Entity\BilletageLignes;
use App\Entity\Billetages;
use App\Entity\Billets;
use App\Entity\Caisses;
use App\Entity\Comptes;
use App\Entity\CriteresDates;
use App\Entity\DetteCreditDivers;
use App\Entity\DeviseIntercaisses;
use App\Entity\DeviseJournees;
use App\Entity\Devises;
use App\Entity\InterCaisses;
use App\Entity\JourneeCaisses;
use App\Entity\ParamComptables;
use App\Entity\RecetteDepenses;
use App\Entity\SystemElectInventaires;
use App\Entity\SystemElectLigneInventaires;
use App\Entity\SystemElects;
use App\Entity\TransfertInternationaux;
use App\Entity\Utilisateurs;
use App\Form\BilletagesType;
use App\Form\ChoisirCaisseType;
use App\Form\CriteresRecherchesJourneeCaissesType;
use App\Form\DeviseJourneesType;
use App\Form\FermetureType;
use App\Form\JourneeCaissesType;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\Tests\Fixture\DumbFoo;

/**
 * @Route("/journeecaisses")
 */
class JourneeCaissesController extends Controller
{
    //const ECHEC=0, SUCCES=1;
    private $journeeCaisse;
    private $utilisateur;
    private $caisse;
    //private $messages;
    //private $paramComptable;

    public function __construct(SessionUtilisateur $sessionUtilisateur)
    {
        $this->utilisateur=$sessionUtilisateur->getUtilisateur();
        //dernière caisse ouverte par l'utilisateur ou null si inexistant
        $this->caisse=$sessionUtilisateur->getLastCaisse();
        //dernière journée de la caisse ou null si inexistant
        $this->journeeCaisse=$sessionUtilisateur->getJourneeCaisse();

        $this->messages=array();


    }

    /**
     * @Route("/", name="journee_caisses_index", methods="GET|POST")
     */
    public function index(Request $request): Response
    {
        $caisse=$request->request->get('caisse')?$request->request->get('caisse'):$request->query->get('caisse');
        $dateDebut=$request->request->get('dateDebut')?$request->request->get('dateDebut'):$request->query->get('dateDebut');
        $dateFin=$request->request->get('dateFin')?$request->request->get('dateFin'):$request->query->get('dateFin');
        $utilisateur=$request->request->get('utilisateur')?$request->request->get('utilisateur'):$request->query->get('utilisateur');
        $limit=60;
        $_page=$request->query->get('_page');
        $offset = ($_page)?($_page-1)*$limit:0;

        if (!$_page) {
            $criteresRecherches=new CriteresDates();
            if($dateDebut) $criteresRecherches->setDateDebut(new \DateTime($dateDebut));
            if($dateFin) $criteresRecherches->setDateFin(new \DateTime($dateFin));
        }else{
            $criteresRecherches=new CriteresDates();
            $criteres=$request->query->get('master');
            $criteres= explode ('|',$criteres);
           // dump($criteres);
           // dump($caisse);
            if (count($criteres)==3) {
                $criteresRecherches->setDateDebut(new \DateTime($criteres[0]));
                $criteresRecherches->setDateFin(new \DateTime($criteres[1]));
                $caisse=($caisse)?$caisse:$criteres[2];
            }
        }

        $form = $this->createForm(CriteresRecherchesJourneeCaissesType::class, $criteresRecherches);
        $form->handleRequest($request);

        $dateDebut=new \DateTime($criteresRecherches->getDateDebut()->format('Y-m-d').' 00:00:00');
        $dateFin=new \DateTime($criteresRecherches->getDateFin()->format('Y-m-d').' 23:59:59');

        $liste = $this->getDoctrine()
            ->getRepository(JourneeCaisses::class)
            ->findRecapitulatifJourneeCaisses($dateDebut, $dateFin, $caisse, $utilisateur, $offset,$limit);
        $pages = round(count($liste)/$limit);

        $request->query->set('master',$dateDebut->format('Y-m-d').'|'.$dateFin->format('Y-m-d').'|'.$caisse);


        $caisses=$this->getDoctrine()->getRepository(Caisses::class)->findAll();
        $utilisateurs=$this->getDoctrine()->getRepository(Utilisateurs::class)->findAll();

        return $this->render('journee_caisses/index.html.twig', [
            'journeeCaisses' => $liste,
            'form' => $form->createView(),
            'pages'=>$pages,
            'path'=>'journee_caisses_index',
            'caisses'=>$caisses,
            'caisse_id'=>$caisse,
            'utilisateurs'=>$utilisateurs,
            'utilisateur_id'=>$utilisateur,
            'criteres'=>$criteresRecherches,
        ]);

    }

    /**
     * @Route("/gerer", name="journee_caisses_gerer", methods="GET|POST")
     */
    public function gerer(Request $request): Response
    {
        //dump($this->journeeCaisse);die();
        /*//si un comptable ouvrir saisie caisse menu depense
        if ($this->get('security.authorization_checker')->isGranted('ROLE_COMPTABLE')) {
            return $this->redirectToRoute('compta_saisie_cmd');
        }*/
        $em=$this->getDoctrine()->getManager();
        $devises = $em->getRepository(Devises::class)->findAll();

        if (!$this->caisse){ // utilisateur n'ayant jamais ouvert de caisse
            return $this->redirectToRoute('journee_caisses_init');
        }

        $this->journeeCaisse=$this->caisse->getLastJournee();

        if (!$this->journeeCaisse){ //Caisse n'ayant aucune dernière journée ouverte
            //initier nouvelle journeeCaisse
            $this->journeeCaisse=$this->initJournee($this->caisse);
        }
        //dump($this->journeeCaisse->getJourneePrecedente()->getDeviseJournees());die();
        switch ($this->journeeCaisse->getStatut()){
            case JourneeCaisses::CLOSE :
                //initier nouvelle journeeCaisse;
                //dump($this->journeeCaisse);die();
                $journeePrecedent=$this->journeeCaisse;
                $this->journeeCaisse=$this->initJournee($this->caisse,$journeePrecedent);
                //dump($this->journeeCaisse);die();
                return $this->render('journee_caisses/ouvrir.html.twig', ['journeeCaisse' => $this->journeeCaisse,'journeeCaisses'=>null,'journeePrecedente'=>$journeePrecedent]);
                break;
            case JourneeCaisses::INITIAL :
                //dump($this->journeeCaisse);die();
                return $this->render('journee_caisses/ouvrir.html.twig', ['journeeCaisse' => $this->journeeCaisse,'journeeCaisses'=>null,'journeePrecedente'=>$this->journeeCaisse->getJourneePrecedente()]);
                break;
            case JourneeCaisses::ENCOURS :
                if ($this->utilisateur->getId()!=$this->journeeCaisse->getUtilisateur()->getId()){ //utilisateur différent de celui de la journee caisse
                    $this->addFlash('error', 'Caisse '.$this->caisse->getCode().' déjà ouverte. Choisissez une autre caisse à ouvrir');
                    return $this->redirectToRoute('journee_caisses_init');
                }
                return $this->render('journee_caisses/gerer.html.twig', ['journeeCaisse' => $this->journeeCaisse,'journeeCaisses'=>null]);
                break;
            default :
                return $this->render('journee_caisses/ouvrir.html.twig', ['journeeCaisse' => $this->journeeCaisse,'journeeCaisses'=>null,'journeePrecedente'=>$this->journeeCaisse->getJourneePrecedente()]);
        }
    }

    /**
     * @Route("/changer", name="journee_caisses_init", methods="GET|POST")
     */
    public function changerCaisse(Request $request): Response
    {
        //refuser le changement de caisse d'un guichetier ayant une caisse toujours ouverte
        if($this->journeeCaisse && $this->caisse->getTypeCaisse()==Caisses::GUICHET) {
            if ($this->journeeCaisse->getStatut() == JourneeCaisses::ENCOURS && $this->utilisateur->getId() == $this->journeeCaisse->getUtilisateur()->getId()) {
                $this->addFlash('error', 'Caisse toujours ouverte. Fermez la avant de changer de caisse');
                return $this->redirectToRoute('journee_caisses_gerer');
            }
        }
        if ($this->isGranted('ROLE_GUICHETIER'))  $form = $this->createForm(UtilisateursLastCaisseType::class, $this->utilisateur,['typeCaisse'=>Caisses::GUICHET]);
        else $form = $this->createForm(UtilisateursLastCaisseType::class, $this->utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($this->utilisateur);
            $em->flush();
            return $this->redirectToRoute('journee_caisses_gerer');
        }

        return $this->render('journee_caisses/init.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verifFerm", name="journee_caisses_veriferFerm", methods="POST")
     */
    public function verifierFermeture(Request $request)
    {
        //dump($this->journeeCaisse); die();
        if (!$this->journeeCaisse){
            $this->addFlash('error', 'ERREUR D\'ENREGISTREMENT RENCONTREE. RECOMMENCEZ !!!');
            return $this->redirectToRoute('journee_caisses_gerer');
        }

        if (!$this->journeeCaisse->getStatut()==JourneeCaisses::CLOSE){
            $this->addFlash('error', 'JOURNEE DEJA FERMEE !!!');
            return $this->redirectToRoute('journee_caisses_gerer');
        }
        //dump($this->journeeCaisse); die();

        //Maintenir les solde de la journee
        $this->addFlash('success', 'MAINTENANCE DES SOLDES DE LA JOURNEE');
        $this->maintenirJournee($this->journeeCaisse);

        //Verifier la validation de toutes les intercaisses
        $this->addFlash('success', 'VERIFICATION DES INTERCAISSES ENTRANTS');
        if (!$this->verifierIntercaisses($this->journeeCaisse->getIntercaisseEntrants())) return $this->redirectToRoute('intercaisses_ajout');

        $this->addFlash('success', 'VERIFICATION DES INTERCAISSES SORTANTS');
        if (!$this->verifierIntercaisses($this->journeeCaisse->getIntercaisseSortants())) return $this->redirectToRoute('intercaisses_ajout');

        $this->addFlash('success', 'VERIFICATION DES INTERCAISSES DEVISES ENTRANTS');
        if (!$this->verifierDeviseIntercaisses($this->journeeCaisse->getDeviseIntercaisseEntrants())) return $this->redirectToRoute('devise_intercaisses_gestion');

        $this->addFlash('success', 'VERIFICATION DES INTERCAISSES DEVISES SORTANTS');
        if (!$this->verifierDeviseIntercaisses($this->journeeCaisse->getDeviseIntercaisseSortants())) return $this->redirectToRoute('devise_intercaisses_gestion');

        return $this->render('journee_caisses/verifierFermeture.html.twig', ['journeeCaisse'=>$this->journeeCaisse]);
    }

    /*
     * @Route("/{id}/maintenir", name="journee_caisses_maintSolde", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")

    public function maintenirSolde(Request $request, JourneeCaisses $journeeCaisse)
    {
        //dump($journeeCaisse);die();
        $journeeCaisse=$this->maintenirJournee($journeeCaisse);

        $this->addFlash('success', 'LES DONNEES SONT BONNES');

        return $this->redirectToRoute('journee_caisse_show', ['id'=>$journeeCaisse->getId()]);
    }*/


    /**
     * @Route("/{id}/maintenance", name="journee_caisses_maintenance", methods="GET|POST")
     */
    public function maintenanceSolde(Request $request, JourneeCaisses $journeeCaisse)
    {
        //dump($this->journeeCaisse); die();
        $copieDeJournee = $this->copierJournee($journeeCaisse);
        $journeeCaisseMaintenue=$this->maintenirJournee($journeeCaisse);
        $ecartJournees = $this->ecartJournees($copieDeJournee, $journeeCaisseMaintenue);

        $dateDebut=($journeeCaisse->getDateOuv())?$journeeCaisse->getDateOuv():new \DateTime('today');
        $dateFin=($journeeCaisse->getDateFerm())?$journeeCaisse->getDateFerm():new \DateTime();

        $return=$caisse=$request->request->get('return')?$request->request->get('return'):$request->query->get('return');

        if($return) return $this->redirectToRoute($return);

        return $this->redirectToRoute('journee_caisses_index',[
            'dateDebut'=>$dateDebut->format('Y-m-d'),
            'dateFin'=>$dateFin->format('Y-m-d'),
        ]);

        //return $this->render('journee_caisses/maintenance.html.twig', ['journeeCaisse' => $copieDeJournee,'journeeCaisseMaintenue'=>$journeeCaisseMaintenue, 'ecartJournees'=>$ecartJournees]);

        //$this->addFlash('success', 'LES DONNEES SONT BONNE');

        //return $this->redirectToRoute('journee_caisse_show', ['id'=>$journeeCaisse->getId()]);
    }

    /**
     * @Route("/fermeture", name="journee_caisses_fermer", methods="FERMERCAISSE")
     */
    public function fermer(Request $request)
    {
        if ($request->getMethod()=='FERMERCAISSE'){ //Action sur la confirmation de la fermeture de caisse

            $em=$this->getDoctrine()->getManager();

            //sécuriser l'opération avec un token
            if ($this->isCsrfTokenValid('fermer'.$this->journeeCaisse->getId(), $request->request->get('_token'))) {
                //comptabiliser l'écart de caisse
                $this->addFlash('success', 'COMPTABILISATION ....');
                $genererCompta=new GenererCompta($em);
                if (!$genererCompta->genComptaFermeture($this->journeeCaisse)){
                    $this->addFlash('error', '==> ECHEC : '. $genererCompta->getErrMessage());
                    return $this->redirectToRoute('journee_caisses_gerer');
                }
                $this->addFlash('success', '==>OK ');

                //fermer la caisse
                $this->journeeCaisse->setDateFerm(new \DateTime());
                $this->journeeCaisse->setStatut(JourneeCaisses::CLOSE);
                $em->persist($this->journeeCaisse);
                $em->flush();

                return $this->redirectToRoute('journee_caisses_etat_de_caisse');
            }
            $this->addFlash('error', 'APPEL DE FERMETURE INCORRECT. Veuillez reprendre SVP');
        }
        return $this->redirectToRoute('journee_caisses_gerer');
    }

    /**
     * @Route("/ouverture", name="journee_caisses_ouvrir", methods="GET|POST|UPDATE")
     */
    public function ouvrir(Request $request):Response
    {
        if (!$this->journeeCaisse){
            $this->addFlash('error', 'ERREUR D\'OUVERTURE RENCONTREE. RECOMMENCEZ !!!');
            return $this->redirectToRoute('journee_caisses_gerer');
        }

        if (!$this->journeeCaisse->getStatut()==JourneeCaisses::ENCOURS){
            $this->addFlash('error', 'JOURNEE DEJA OUVERTE !!!');
            return $this->redirectToRoute('journee_caisses_gerer');
        }
        $em=$this->getDoctrine()->getManager();
        //$genererCompta=new GenererCompta($em);
        //$genererCompta->genComptaEcart($this->utilisateur, $this->caisse, 'Ecart ouverture' . $this->journeeCaisse, $this->journeeCaisse->getMEcartOuv(), $this->journeeCaisse);
        $this->journeeCaisse->setStatut(JourneeCaisses::ENCOURS);
        $this->journeeCaisse->setDateOuv(new \DateTime());
        $this->journeeCaisse->setUtilisateur($this->utilisateur);

        $this->setSoldeFerm();
        $em->persist($this->journeeCaisse);

        $em->flush();

        return $this->redirectToRoute('journee_caisses_gerer');
    }

    /**
     * @Route("/etat/caisse", name="journee_caisses_etat_de_caisse", methods="GET|POST|UPDATE")
     */
    public function etatDeCaisse(Request $request): Response
    {
        /*$dateDeb = new \DateTime("01-11-2018");
        $dateFin = new \DateTime('now');
        $limit=10;
        $_page=$request->query->get('_page');
        $offset = ($_page)?($_page-1)*$limit:0;
        $caisse=$this->caisse;
        if ($this->isGranted('ROLE_ADMIN'))$caisse=null;

        $journeeCaisses = $this->getDoctrine()
            ->getRepository(JourneeCaisses::class)
            ->findJourneeCaisses($caisse,$offset,$limit);
        $pages = round(count($journeeCaisses)/$limit);

        return $this->render('journee_caisses/etat_de_caisse.html.twig', [
            'journee_caisses' => $journeeCaisses,
            'caisse'=>$caisse,
            'pages'=>$pages,
            'journeeCaisse' => null,
            //'form' => $form->createView()
        ]);*/
        return $this->redirectToRoute('journee_caisses_index',['caisse'=>$this->caisse->getId()]);

    }

    /**
     * @Route("/etat/compense", name="journee_caisses_compense", methods="GET|POST|UPDATE")
     */
    public function etatCompense(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $date = new \DateTime('now');
        $date->setDate(2019, 1, 25);
        if ($request->get('date'))
            $date = new \DateTime($request->get('date'));
        $journeeCaisses = $em->getRepository(JourneeCaisses::class)->getJourneeCaissesDuJour($date);
        $recapJourneeCaisses = $em->getRepository(JourneeCaisses::class)->getRecapJourneeCaisses($date);
        //dump($recapJourneeCaisses);die();

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
     * @Route("/etat/tresorerie", name="journee_caisses_tresorerie", methods="GET|POST|UPDATE")
     */
    public function etatTresorerie(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $date = new \DateTime();
        $dateDeb=new \DateTime();
        $dateFin=new \DateTime();
        $debut = $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('d'));
        $fin = $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('d'));
        $limit=10;
        if (($request->query->get('_dateDeb')))
            $debut = new \DateTime($request->query->get('_dateDeb'));
        elseif ($request->query->get('dateDeb'))
            $debut = new \DateTime($request->query->get('dateDeb'));
        if ($request->query->get('_dateFin'))
            $fin = new \DateTime($request->query->get('_dateFin'));
        $tresoOuv = $em->getRepository(JourneeCaisses::class)->getOuvertureTresorerie($debut,$fin);
        $tresoRecap = $em->getRepository(JourneeCaisses::class)->getCompenseRecetteDepenseEcartTresorerie($debut,$fin);
        $tresoFerm = $em->getRepository(JourneeCaisses::class)->getFermetureTresorerie($debut,$fin);
        $tresoAppro = $em->getRepository(JourneeCaisses::class)->getApproTresorerie($debut,$fin);
        $tresoDevise = $em->getRepository(DeviseJournees::class)->getDeviseTresorerie($debut,$fin);
        //dump($tresoOuv);die();

        return $this->render('journee_caisses/etat_tresorerie.html.twig'
            , [
                'treso_ouv' => $tresoOuv,
                'treso_recap' => $tresoRecap,
                'treso_ferm' => $tresoFerm,
                'treso_appro' => $tresoAppro,
                'treso_devise' => $tresoDevise,
                'dateDeb'=>$debut,
                'dateFin'=>$fin,
            ]
        );

    }

    /**
     * @Route("/detail/tresorerie", name="detail_ouverture_tresorerie", methods="GET")
     */
    public function getDetailTresorerie(Request $request): Response
    {
        $date = new \DateTime();
        $dateDeb=new \DateTime();
        $dateFin=new \DateTime();
        $debut = $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('d'));
        $fin = $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('d'));
        $limit=10;
        if (($request->query->get('_dateDeb')))
            $debut = new \DateTime($request->query->get('_dateDeb'));
        if ($request->query->get('_dateFin'))
            $fin = new \DateTime($request->query->get('_dateFin'));
        $date = new \DateTime('');
        if ($request->query->get('_date'))$date = new \DateTime($request->query->get('_date'));
        if (($request->query->get('_etat')))
            $etat = $request->query->get('_etat');
        $champ = $request->query->get('_champ');
        $journeeCaisse=$this->getDoctrine()->getRepository(JourneeCaisses::class)->getDetailsTresorerie($etat,$debut,$fin);

        //dump($req);die();

        return $this->render('journee_caisses/detail_etat_tresorerie.html.twig', [
            'journee_caisses' => $journeeCaisse,
            'etat'=>$etat,
            'champ'=>$champ,
            'src'=>'bd',]);
    }
    /**
     * @Route("/detail/compense/recette/depense/tresorerie", name="detail_compense_recette_depense__tresorerie", methods="GET")
     */
    public function getDetailCompenseRecetteDepenseEcartTresorerie(Request $request): Response
    {
        $date = new \DateTime();
        $dateDeb=new \DateTime();
        $dateFin=new \DateTime();
        $debut = $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('d'));
        $fin = $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('d'));
        $limit=10;
        if (($request->query->get('_dateDeb')))
            $debut = new \DateTime($request->query->get('_dateDeb'));
        if ($request->query->get('_dateFin'))
            $fin = new \DateTime($request->query->get('_dateFin'));
        $date = new \DateTime('');
        if ($request->query->get('_date'))$date = new \DateTime($request->query->get('_date'));
        $tresoOuv = $this->getDoctrine()
            ->getRepository(JourneeCaisses::class)
            ->getDetailsOuvertureTresorerie($debut,$fin);
        //dump($deviseJournees);die();
        return $this->render('journee_caisses/detail_etat_tresorerie.html.twig', [
            'journee_caisses' => $tresoOuv,
            'etat'=>'crd',
            'src'=>'bd',]);
    }

    /**
     * @Route("/{id}", name="journee_caisse_show", methods="GET")
     */
    public function show(JourneeCaisses $journeeCaisse): Response
    {
        return $this->render('journee_caisses/gerer.html.twig', ['journeeCaisse' => $journeeCaisse,'journeeCaisses'=>null]);
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

    /*
    public function comptabiliserFermeture(JourneeCaisses $journeeCaisse){
        $paramComptable=$this->getDoctrine()->getRepository(ParamComptables::class)->findOneBy(['codeStructure'=>'YESBO']);
        $genererCompta=new GenererCompta($this->getDoctrine()->getManager());
        $genererCompta->genComptaIntercaisse($journeeCaisse->getUtilisateur(),$journeeCaisse->getCaisse(),$paramComptable, $journeeCaisse->getMIntercaisses());
        $genererCompta->genComptaFermeture($journeeCaisse->getUtilisateur(),$journeeCaisse->getCaisse(),$paramComptable,$journeeCaisse->getMIntercaisses(),$journeeCaisse->getMReceptionTrans() - $journeeCaisse->getMEmissionTrans(),$journeeCaisse->getMCvd(),$journeeCaisse->getMEcartFerm());
        $genererCompta->genComptaCompense($journeeCaisse->getUtilisateur(),$journeeCaisse->getCaisse(),$paramComptable,$journeeCaisse->getMReceptionTrans() - $journeeCaisse->getMEmissionTrans());
        $genererCompta->genComptaCvDevise($journeeCaisse->getUtilisateur(),$journeeCaisse->getCaisse(),$journeeCaisse->getMCvd());
        $genererCompta->genComptaEcart($journeeCaisse->getUtilisateur(),$journeeCaisse->getCaisse(), 'Ecart Fermeture'. $journeeCaisse, $journeeCaisse->getMEcartFerm());
    }
    */

    private function initJournee(Caisses $caisse, JourneeCaisses $journeeCaissePrecedent=null)
    {
        $error = false;
        $em = $this->getDoctrine()->getManager();

        $newJournee = new JourneeCaisses($em);
        $newJournee->setCaisse($caisse)
            ->setUtilisateur($this->utilisateur)
            ->setStatut(JourneeCaisses::INITIAL)
            ->setDateOuv(new \DateTime());

        if (!$journeeCaissePrecedent) { //initialiser à partenir de néant !!!

            $em->persist($newJournee);
            foreach ($em->getRepository(Devises::class)->findAll() as $devise){
                $newdvj = new DeviseJournees($newJournee, $devise);
                $newJournee->addDeviseJournee($newdvj);
                $em->persist($newdvj);
            }
            $em->flush();
            //dump($newJournee);die();
            return $newJournee;
        }

        $newJournee->setJourneePrecedente($journeeCaissePrecedent)
            ->setMCreditDiversOuv($journeeCaissePrecedent->getMCreditDiversFerm())
            ->setMDetteDiversOuv($journeeCaissePrecedent->getMDetteDiversFerm())
            ->setMCreditDiversFerm($journeeCaissePrecedent->getMCreditDiversFerm())
            ->setMDetteDiversFerm($journeeCaissePrecedent->getMDetteDiversFerm())
            ->setMSoldeElectOuv($journeeCaissePrecedent->getMSoldeElectFerm())
            ->setMLiquiditeOuv($journeeCaissePrecedent->getMLiquiditeFerm())
            //->setBilletOuv($journeeCaissePrecedent->getBilletOuv())
            //->setBilletFerm($journeeCaissePrecedent->getBilletFerm())
            //->setSystemElectInventOuv($journeeCaissePrecedent->getSystemElectInventOuv())
            //->setSystemElectInventFerm($journeeCaissePrecedent->getSystemElectInventFerm())
            ->setDetailLiquiditeOuv($journeeCaissePrecedent->getDetailLiquiditeFerm())
            ->setDetailSoldeElectOuv($journeeCaissePrecedent->getDetailSoldeElectFerm())
        ;


        foreach ( $journeeCaissePrecedent->getDetteCredits() as $detteCredit) {
            if ($detteCredit->getStatut()== DetteCreditDivers::CREDIT_EN_COUR or $detteCredit->getStatut()== DetteCreditDivers::DETTE_EN_COUR){
                //$detteCredit->setJourneeCaisseActive($newJournee);
                $newDetteCredit = new DetteCreditDivers($newJournee);
                $newDetteCredit->setJourneeCaisseActive($newJournee);
                $newDetteCredit->setStatut($detteCredit->getStatut());
                $newDetteCredit->setMDette($detteCredit->getMDette());
                $newDetteCredit->setMCredit($detteCredit->getMCredit());
                $newDetteCredit->setLibelle($detteCredit->getLibelle());
                $newDetteCredit->setDateCreation($detteCredit->getDateCreation());
                $newDetteCredit->setUtilisateurCreation($detteCredit->getUtilisateurCreation());
                $newDetteCredit->setJourneeCaisseCreation($detteCredit->getJourneeCaisseCreation());
                //dump($newDetteCredit->getMCredit());

                $em->persist($newDetteCredit);
            }
        }
        /*if (count($journeeCaissePrecedent->getBilletFerm()->getBilletageLignes())>0) {
            foreach ($journeeCaissePrecedent->getBilletFerm()->getBilletageLignes() as $bl) {
                //$newJournee->getBilletOuv()->addBilletageLignes($bl);
                //$newJournee->getBilletOuv()->setBilletageLigne($newJournee->getBilletOuv()->getBilletageLigne() . '' . $bl->getValeurBillet() . 'x' . $bl->getNbBillet() . ';');
                $newJournee->setDetailLiquiditeOuv($newJournee->getDetailLiquiditeOuv() . '' . $bl->getValeurBillet() . 'x' . $bl->getNbBillet() . ';');
                $em->persist($bl);
            }
        }else {dump("billetage fermeture precedente n'avait aucune ligne");$error=true;}

        if (count($journeeCaissePrecedent->getSystemElectInventFerm()->getSystemElectLigneInventaires())>0) {
            foreach ($journeeCaissePrecedent->getSystemElectInventFerm()->getSystemElectLigneInventaires() as $seli) {
                $newJournee->getSystemElectInventOuv()->addSystemElectLigneInventaires($seli);
                $newJournee->getSystemElectInventOuv()->setSystemElectLigneInventaire($newJournee->getSystemElectInventOuv()->getSystemElectLigneInventaire() . ';' . $seli->getIdSystemElect()->getLibelle() . '=' . $seli->getSolde());
                $newJournee->setDetailSoldeElectOuv($newJournee->getDetailSoldeElectOuv() . ';' . $seli->getIdSystemElect()->getLibelle() . '=' . $seli->getSolde());
                $em->persist($seli);
            }
        }
        else {dump("Electronique fermeture precedente n'avait aucune ligne");$error=true;}
        */


        foreach ($journeeCaissePrecedent->getDeviseJournees() as $dvj){
            $exist=false;
            foreach ($newJournee->getDeviseJournee() as $dj){
                if ($dj->getDevise() == $dvj->getDevise())
                    $exist = true;
            }
            if (!$exist){
            $newdvj = new DeviseJournees($newJournee, $dvj->getDevise());
            $newdvj->setQteOuv($dvj->getQteFerm())
                ->setDetailLiquiditeOuv($dvj->getDetailLiquiditeFerm());
        }
        /*if ($dvj->getBilletFerm()->getBilletageLignes()) {
                foreach ($dvj->getBilletFerm()->getBilletageLignes() as $bl) {
                    //$newdvj->getBilletOuv()->addBilletageLignes($bl);
                    //$newdvj->getBilletOuv()->setBilletageLigne($newdvj->getBilletOuv()->getBilletageLigne() . '' . $bl->getValeurBillet() . 'x' . $bl->getNbBillet() . ';');
                    $newdvj->setDetailLiquiditeOuv($newdvj->getDetailLiquiditeOuv() . '' . $bl->getValeurBillet() . 'x' . $bl->getNbBillet() . ';');
                }
            }*/

            //////TEST D'OCCURENCE/////////
            $occurence=0;
            foreach ($newJournee->getDeviseJournee() as $dj){
                if ($newdvj->getDevise()==$dj->getDevise()){
                    $occurence=$occurence+1;
                }
            }
            if ($occurence==0)
            $newJournee->addDeviseJournee($newdvj);

            //if ($error){die();}
            $em->persist($newdvj);

        }

        $devises = $em->getRepository(Devises::class)->findAll();
        if ($newJournee->getDeviseJournee()->isEmpty()){
            foreach ($devises as $devise){
                $deviseJournee = new DeviseJournees($journeeCaissePrecedent,$devise);
                $newJournee->addDeviseJournee($deviseJournee);
                $em->persist($deviseJournee);
            }

        }
        //////SUPPRESSION D'EVENTUELLES LIGNES SUPPLEMENTAIRES
        while($newJournee->getDeviseJournee()->count()>count($devises)) {
            $occurence = 0;
            foreach ($newJournee->getDeviseJournee() as $dj1) {
                foreach ($newJournee->getDeviseJournee() as $dj2) {
                    if ($dj1->getBillet() == $dj2->getBillet() && $dj1 != $dj2) {
                        $newJournee->removeDeviseJournee($dj2);
                        $occurence = $occurence + 1;
                        break;
                    }
                }
                if ($occurence > 0) {
                    break;
                }
            }
        }

        //dump($newJournee->getDeviseJournee()->count());die();
        $em->persist($newJournee);
        $em->flush();
        return $newJournee;


    }

    private function setSoldeFerm(){

        //maj solde fermeture avec les données d'ouverture
        $error=false;
        /*if (count($this->journeeCaisse->getBilletOuv()->getBilletageLignes())>0) {
            foreach ($this->journeeCaisse->getBilletOuv()->getBilletageLignes() as $billetageLigneOuv) {
                $this->journeeCaisse->getBilletFerm()->addBilletageLignes($billetageLigneOuv);
                $this->journeeCaisse->getBilletFerm()->setBilletageLigne($this->journeeCaisse->getBilletFerm()->getBilletageLigne() . '' . $billetageLigneOuv->getValeurBillet() . 'x' . $billetageLigneOuv->getNbBillet() . ';');
            }
        }else {dump("billetage ouverture n'avait aucune ligne");$error=true;}*/

        $this->journeeCaisse->setMLiquiditeFerm($this->journeeCaisse->getMLiquiditeOuv());
        $this->journeeCaisse->setDetailLiquiditeFerm($this->journeeCaisse->getDetailLiquiditeOuv());

        //soldes électroniques
        /*if(count($this->journeeCaisse->getSystemElectInventOuv()->getSystemElectLigneInventaires())>0) {
            foreach ($this->journeeCaisse->getSystemElectInventOuv()->getSystemElectLigneInventaires() as $seli) {
                $this->journeeCaisse->getSystemElectInventFerm()->addSystemElectLigneInventaires($seli);
                $this->journeeCaisse->getSystemElectInventFerm()->setSystemElectLigneInventaire($this->journeeCaisse->getSystemElectInventFerm()->getSystemElectLigneInventaire().''.$seli->getIdSystemElect()->getLibelle().'='.$seli->getSolde().';');
                $this->journeeCaisse->setDetailSoldeElectFerm($this->journeeCaisse->getDetailSoldeElectFerm().''.$seli->getIdSystemElect()->getLibelle().'='.$seli->getSolde().';');
            }
        }else {dump("Electronique ouverture n'avait aucune ligne");$error=true;}*/

        $this->journeeCaisse->setMSoldeElectFerm($this->journeeCaisse->getMSoldeElectOuv());
        $this->journeeCaisse->setDetailSoldeElectFerm($this->journeeCaisse->getDetailSoldeElectOuv());

        //soldes fermeture devises
        foreach ($this->journeeCaisse->getDeviseJournees() as $dvj){
            $dvj->setQteFerm($dvj->getQteOuv());
            $dvj->setDetailLiquiditeFerm($dvj->getDetailLiquiditeOuv());
            /*if (count($dvj->getBilletOuv()->getBilletageLignes())>0) {
                foreach ($dvj->getBilletOuv()->getBilletageLignes() as $bl) {
                    $dvj->getBilletFerm()->addBilletageLignes($bl);
                    $dvj->getBilletFerm()->setBilletageLigne($dvj->getBilletFerm()->getBilletageLigne() . '' . $bl->getValeurBillet() . 'x' . $bl->getNbBillet() . ';');
                    $dvj->setDetailLiquiditeFerm($dvj->getDetailLiquiditeFerm() . '' . $bl->getValeurBillet() . 'x' . $bl->getNbBillet() . ';');
                }
            }
            else{dump("billetage ouverture de devise n'avait aucune ligne"); $error=true;}*/
        }
        //if ($error)die();

    }

    private function verifierIntercaisses($intercaisses){
        $test=true;
        foreach ($intercaisses as $intercaisse){
            if ($intercaisse->getStatut()==InterCaisses::INITIE){
                $test=false;
                $this->addFlash('error', $intercaisse->getMIntercaisse().' ==>ECHEC. Validez ou annuler toutes les intercaisses avant la cloture ! ! !');

            }else{
                if ($intercaisse->getStatut()!=InterCaisses::ANNULE)
                $this->addFlash('success', $intercaisse->getMIntercaisse().' ==>OK');

            }
        }
        return $test;
    }

    private function verifierDeviseIntercaisses($intercaisses){
        $test=true;
        foreach ($intercaisses as $intercaisse){
            if ($intercaisse->getStatut()==DeviseIntercaisses::INIT){
                $test=false;
                $this->addFlash('error',$intercaisse->getObservations().' ==>ECHEC. Validez ou annuler toutes les intercaisses avant la cloture ! ! !');

            }else{
                if ($intercaisse->getStatut()!=DeviseIntercaisses::ANNULE)
                $this->addFlash('success', $intercaisse->getObservations().' ==>OK');

            }
        }
        return $test;
    }

    private function maintenirJournee(JourneeCaisses $journeeCaisse){
        $journeeCaisse->maintenirToutSolde();
        /*->maintenirMLiquiditeFerm()
            ->maintenirMSoldeElectFerm()
            ->maintenirMIntercaisses()
            ->maintenirMDepotRetraits()
            ->maintenirDetteCreditDiversFerm()
            ->maintenirMCvd()
            ->maintenirRecetteDepenses()
            ->maintenirTransfertsInternationaux()*/
        $this->getDoctrine()->getManager()->persist($journeeCaisse);
        $this->getDoctrine()->getManager()->flush();
        return $journeeCaisse;
    }
    /*private function maintenirJourneePersonnalise(JourneeCaisses $journeeCaisse){
        $journeeCaisse
            ->maintenirMLiquiditeFerm()
            ->maintenirMSoldeElectFerm()
            ->maintenirMIntercaisses()
            ->maintenirMDepotRetraits()
            ->maintenirDetteCreditDiversFerm()
            ->maintenirMCvd()
            ->maintenirRecetteDepenses()
            ->maintenirTransfertsInternationaux();
        //$this->getDoctrine()->getManager()->persist($journeeCaisse);
        //$this->getDoctrine()->getManager()->flush();
        return $journeeCaisse;
        }*/
    private function copierJournee(JourneeCaisses $journeeCaisse){
        $em = $this->getDoctrine()->getManager();
        $newjournee = new JourneeCaisses($em);
        $newjournee->setMSoldeElectFerm($journeeCaisse->getMSoldeElectFerm())
            ->setMLiquiditeFerm($journeeCaisse->getMLiquiditeFerm())
            ->setMCreditDiversFerm($journeeCaisse->getMCreditDiversFerm())
            ->setMDetteDiversFerm($journeeCaisse->getMDetteDiversFerm())
            ->setMIntercaisses($journeeCaisse->getMIntercaisses())
            ->setMDepotClient($journeeCaisse->getMDepotClient())
            ->setMRetraitClient($journeeCaisse->getMRetraitClient())
            ->setMEmissionTrans($journeeCaisse->getMEmissionTrans())
            ->setMReceptionTrans($journeeCaisse->getMReceptionTrans())
            ->setMRecette($journeeCaisse->getMRecette())
            ->setMDepense($journeeCaisse->getMDepense())
            ->setMCvd($journeeCaisse->getMCvd())
            ->setMEcartFerm();
        return $newjournee;
    }
    private function ecartJournees(JourneeCaisses $journeeCaisse, JourneeCaisses $journeeCaisseMaintenue){
        $em = $this->getDoctrine()->getManager();
        $newjournee = new JourneeCaisses($em);
        $newjournee->setMSoldeElectFerm($journeeCaisse->getMSoldeElectFerm() - $journeeCaisseMaintenue->getMSoldeElectFerm())
            ->setMLiquiditeFerm($journeeCaisse->getMLiquiditeFerm() - $journeeCaisseMaintenue->getMLiquiditeFerm())
            ->setMCreditDiversFerm($journeeCaisse->getMCreditDiversFerm() - $journeeCaisseMaintenue->getMCreditDiversFerm())
            ->setMDetteDiversFerm($journeeCaisse->getMDetteDiversFerm() - $journeeCaisseMaintenue->getMDetteDiversFerm())
            ->setMIntercaisses($journeeCaisse->getMIntercaisses() - $journeeCaisseMaintenue->getMIntercaisses())
            ->setMDepotClient($journeeCaisse->getMDepotClient() - $journeeCaisseMaintenue->getMDepotClient())
            ->setMRetraitClient($journeeCaisse->getMRetraitClient() - $journeeCaisseMaintenue->getMRetraitClient())
            ->setMEmissionTrans($journeeCaisse->getMEmissionTrans() - $journeeCaisseMaintenue->getMEmissionTrans())
            ->setMReceptionTrans($journeeCaisse->getMReceptionTrans() - $journeeCaisseMaintenue->getMReceptionTrans())
            ->setMRecette($journeeCaisse->getMRecette() - $journeeCaisseMaintenue->getMRecette())
            ->setMDepense($journeeCaisse->getMDepense() - $journeeCaisseMaintenue->getMDepense())
            ->setMCvd($journeeCaisse->getMCvd() - $journeeCaisseMaintenue->getMCvd());
            //->setMEcartFerm();
        return $newjournee;
    }
    /**
     * @Route("/{id}/listingtransferts", name="journee_caisses_transferts", methods="GET|POST")
     */
    public function listingTransferts(JourneeCaisses $journeeCaisse){
        $listingTransferts=$this->getDoctrine()->getRepository(TransfertInternationaux::class)
            ->findListingTransferts(null, null, null, null, null,$journeeCaisse);
        return $this->render('transfert_internationaux/liste_journeeCaisse.html.twig',
            ['listingTransferts' => $listingTransferts,
                'journeeCaisse' => $journeeCaisse,
            ]);
    }

    /**
     * @Route("/{id}/listingrecettedepenses", name="journee_caisses_recetteDepenses", methods="GET|POST")
     */
    public function listingrecettedepenses(JourneeCaisses $journeeCaisse): Response
    {
        $listings=$this->getDoctrine()->getRepository(RecetteDepenses::class)
            ->findListingRecetteDepenses(null, null,null,null,null,null,null, $journeeCaisse);
        return $this->render('recette_depenses/liste_journee_caisses.html.twig',
            ['journeeCaisse' => $journeeCaisse,
                'recetteDepenses'=>$listings,

            ]);
    }

    /**
     * @Route("/ecarts/", name="journee_caisses_ecarts_utilisateurs", methods="GET|POST")
     */
    public function ecartsUtilisateurs(Request $request): Response
    {
        $annee=$request->request->get('annee')?$request->request->get('annee'):$request->query->get('annee');

        if(!$annee) {
            $now=new \DateTime();
            $annee=$now->format('Y');
        }

        $listings=$this->getDoctrine()->getRepository(JourneeCaisses::class)
            ->tableauCroiseEcart($annee);

        return $this->render('journee_caisses/tc_ecarts.html.twig',
            ['ecarts'=>$listings,
                'annee'=>$annee,
            ]);
    }
}
