<?php

namespace App\Controller;

use App\Entity\CriteresDates;
use App\Entity\DeviseJournees;
use App\Entity\Entreprises;
use App\Entity\LigneSalaires;
use App\Entity\ParamComptables;
use App\Entity\RecetteDepenses;
use App\Entity\SystemTransfert;
use App\Entity\TransfertInternationaux;
use App\Entity\Zones;
use App\Form\CriteresDatesType;
use App\Form\trimestreType;
use App\Repository\EntreprisesRepository;
use App\Utils\SessionUtilisateur;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EtatRepository;

/**
 * @Route("/etats")
 */
class EtatsController extends AbstractController
{
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
     * @Route("/", name="etats")
     */
    public function index()
    {
        return $this->render('etats/index.html.twig', [
            'controller_name' => 'EtatsController',
        ]);
    }
    /**
     * @Route("/transfert1", name="etats_rapport_transfert1", methods="GET|POST|UPDATE")
     */
    public function transfert1(Request $request): Response
    {
        $date = new \DateTime();

        $em = $this->getDoctrine();
        $form = $this->createForm(DateTimeType::class, $date);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $date = $form->getData();
            //dump($date); die();
        }
        //dump($date); die();
        $zones = $em->getRepository(Zones::class)->trouverZone();
        //$etatTransfert = $em->getRepository(TransfertInternationaux::class)->trouverTransfert($zone[0],$date);
        //$etatTransfertTypeZone = $em->getRepository(TransfertInternationaux::class)->trouverTransfertTypeZone($zone[0],$date);
        $etatTransfertType = $em->getRepository(TransfertInternationaux::class)->trouverTransfertType($date);
        $entreprise = $em->getRepository(Entreprises::class)->findDetailsEntreprise();
        $dateDeb=new \DateTime('2019-01-01 00:00:00');
        $dateFin=new \DateTime('2019-01-01 00:00:00');
        $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('1'));
        $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('t'));
        //dump($etatTransfert);die();
        return $this->render('etats/transfert1.html.twig', [
            //'etat' => $etatTransfert,
            'form' => $form->createView(),
            'zones' => $zones,
            'etatType' => $etatTransfertType,
            'entreprise' => $entreprise,
            'dateDeb' => $dateDeb,
            'dateFin' => $dateFin,
        ]);
    }

    public function afficherTransfertType(SystemTransfert $type,Zones $zone, \DateTime $date)
    {
        //$date = new \DateTime();
        $dateDeb=new \DateTime('2019-01-01 00:00:00');
        $dateFin=new \DateTime('2019-01-01 00:00:00');
        $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('1'));
        $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('t'));
        //dump($type);die();
        $em = $this->getDoctrine();

        $etatTransfertTypeZone = $em->getRepository(TransfertInternationaux::class)->trouverTransfertTypeZone($type,$zone,$date);
        //dump($etatTransfertTypeZone);die();
        //dump($dateDeb);die();
        return $this->render('etats/transfertType.html.twig', [
            'etatTypeZone' => $etatTransfertTypeZone,
            'dateDeb' => $dateDeb,
            'date' => $date,
            'dateFin' => $dateFin,
        ]);

    }

    public function afficherTransfertZone(Zones $zone, SystemTransfert $type,\DateTime $date)
    {
        //$date = new \DateTime();
        $em = $this->getDoctrine();
        $etatTransfert = $em->getRepository(TransfertInternationaux::class)->trouverTransfert($type,$zone,$date);
        //dump($etatTransfert);die();
        return $this->render('etats/transfertZone.html.twig', [
            'etat' => $etatTransfert,
            'zone'=>$zone
            ]);

    }

    /**
     * @Route("/transfert", name="etats_rapport_transfert", methods="GET|POST|UPDATE")
     */
    public function transfert(Request $request): Response
    {
        $date = new \DateTime();
        $imprimer = false;
        $em = $this->getDoctrine();
        //dump($request);die();
        if ($request->get('date')){
            $date = new \DateTime($request->get('date'));
            $imprimer = true;
        }
        $form = $this->createForm(DateTimeType::class, $date);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $date = $form->getData();
        }
        //dump($date); die();
        $etatTransfert = $em->getRepository(TransfertInternationaux::class)->trouverTransfert($date);
        $etatTransfertTypeZone = $em->getRepository(TransfertInternationaux::class)->trouverTransfertTypeZone($date);
        $etatTransfertType = $em->getRepository(TransfertInternationaux::class)->trouverTransfertType($date);
        $entreprise = $em->getRepository(Entreprises::class)->findDetailsEntreprise();
        $dateDeb=new \DateTime('2019-01-01 00:00:00');
        $dateFin=new \DateTime('2019-01-01 00:00:00');
        $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('1'));
        $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('t'));
        //dump($etatTransfert);die();
        if($imprimer)
            return $this->render('etats/etat_transfert_bceao_impression.html.twig', [
                'etat' => $etatTransfert,
                //'form' => $form->createView(),
                'etatTypeZone' => $etatTransfertTypeZone,
                'etatType' => $etatTransfertType,
                'entreprise' => $entreprise,
                'dateDeb' => $dateDeb,
                'dateFin' => $dateFin,
            ]);
        return $this->render('etats/transfert.html.twig', [
            'etat' => $etatTransfert,
            'form' => $form->createView(),
            'etatTypeZone' => $etatTransfertTypeZone,
            'etatType' => $etatTransfertType,
            'entreprise' => $entreprise,
            'dateDeb' => $dateDeb,
            'dateFin' => $dateFin,
            //'imprimer' => $imprimer
        ]);
    }

    /**
     * @Route("/transfert/apercue", name="etats_rapport_transfert_apercu", methods="GET|POST|UPDATE")
     */
    public function apercue($etatTransfert,$etatTransfertTypeZone,$etatTransfertType,$entreprise,$dateDeb,$dateFin){
        return $this->render('etats/etat_transfert_bceao_impression.html.twig', [
            'etat' => $etatTransfert,
            //'form' => $form->createView(),
            'etatTypeZone' => $etatTransfertTypeZone,
            'etatType' => $etatTransfertType,
            'entreprise' => $entreprise,
            'dateDeb' => $dateDeb,
            'dateFin' => $dateFin,
        ]);
    }
    /**
     * @Route("/devises", name="etats_rapport_devises")
     */
    public function devises(Request $request)
    {
        $date = new \DateTime();
        $dateDeb = new  \DateTime();
        $dateFin = new  \DateTime();
        $imprimer = false;
        $trimestre = intval($date->format('m')/3)+1;

        if ($request->get('date')){
            $date = new \DateTime($request->get('date'));
            $trimestre = $request->get('trimestre');
            $imprimer = true;
            //dump($trimestre);die();
        }
        $default = ['an'=>$date,'trimestre'=>1];
        $form = $this->createForm(trimestreType::class, $default);
        //dump($dateDeb); dump($dateFin);die();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $date = $form['an']->getData();
            $trimestre = intval( $form['trimestre']->getData());
            //dump($trimestre);die();
        }
        //dump($trimestre);die();
        $moisDepart = ($trimestre-1)*3 +1;
        $dateDeb->setDate($date->format('Y'),$moisDepart,$date->format('01'));
        $dateFin->setDate($date->format('Y'),$moisDepart + 3,$date->format('0'));
        $em = $this->getDoctrine();

        $entreprise = $em->getRepository(Entreprises::class)->findDetailsEntreprise();
        $etatDevise = $em->getRepository(DeviseJournees::class)->getDeviseTresorerie($dateDeb, $dateFin);

        if($imprimer)
            return $this->render('etats/etat_devise_bceao_impression.html.twig', [
                'etat' => $etatDevise,
                'entreprise' => $entreprise,
                'dateDeb' => $dateDeb,
                'dateFin' => $dateFin,
            ]);
        return $this->render('etats/devises.html.twig', [
            'etat' => $etatDevise,
            'entreprise' => $entreprise,
            'dateDeb' => $dateDeb,
            'dateFin' => $dateFin,
            'trimestre'=>$trimestre,
            'form'=>$form->createView(),
        ]);
    }
    /**
     * @Route("/devises/apercue", name="etats_rapport_devise_apercu", methods="GET|POST|UPDATE")
     */
    public function apercueDevise($etatDevise,$entreprise,$dateDeb,$dateFin){
        return $this->render('etats/etat_devise_bceao_impression.html.twig', [
            'etat' => $etatDevise,
            'entreprise' => $entreprise,
            'dateDeb' => $dateDeb,
            'dateFin' => $dateFin,
        ]);
    }

    /**
     * @Route("/gestion", name="etats_rapport_gestion", methods="GET|POST|UPDATE")
     */
    public function rapportGestion(Request $request)
    {
        /*$date=$request->request->get('date')?$request->request->get('date'):$request->query->get('date');


        //$moisPrecedentDebut=new \DateTime($date);
        //$moisPrecedentFin=new \DateTime($date);
        $auj=new \DateTime(); $moisEncours=$auj->format('m');
        
        $date=new \DateTime($date);

        $form = $this->createForm(DateTimeType::class, $date);
        $form->handleRequest($request);*/

        $dateDebut=$request->request->get('dateDebut')?$request->request->get('dateDebut'):$request->query->get('dateDebut');
        $dateFin=$request->request->get('dateFin')?$request->request->get('dateFin'):$request->query->get('dateFin');

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

        /*$mois=$date->format('m');
        
        $debutMois=new \DateTime($date->format('Y-').$mois.'-01');
        $moisSuiv=$mois+1;
        $finMois=new \DateTime($date->format('Y-').$moisSuiv.'-00');*/
        
        //if ($mois==$moisEncours){
        $LigneRapports=new ArrayCollection();
        $recetteDepenses=$this->getDoctrine()->getRepository(RecetteDepenses::class)
            ->getSumRecetteDepensesParAgence($criteresRecherches->getDateDebut(),$criteresRecherches->getDateFin());

        foreach ($recetteDepenses as $recetteDepense){
            $salaires=$this->getDoctrine()->getRepository(LigneSalaires::class)
                ->getSumSalairesParAgence($criteresRecherches->getDateDebut(),$criteresRecherches->getDateFin(),$recetteDepense['agence']);
            $mCoutSalaire=($salaires)?$salaires['mCoutSalaire']:0;
            $LigneRapports->add(['agence'=>$recetteDepense['agence']
                ,'dateDebutRapport'=>$criteresRecherches->getDateDebut()
                ,'dateFinRapport'=>$criteresRecherches->getDateFin()
                ,'mRecette'=>$recetteDepense['mRecette']
                ,'mDepense'=>$recetteDepense['mDepense']
                ,'mCoutSalaire'=>$mCoutSalaire]);
        }
            

        

        return $this->render('etats/tc_gestion_agences.html.twig',[
            'LigneRapports'=>$LigneRapports,
            'form'=>$form->createView(),
        ]);
        
    }


}
