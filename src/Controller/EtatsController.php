<?php

namespace App\Controller;

use App\Entity\DeviseJournees;
use App\Entity\Entreprises;
use App\Entity\ParamComptables;
use App\Entity\SystemTransfert;
use App\Entity\TransfertInternationaux;
use App\Entity\Zones;
use App\Repository\EntreprisesRepository;
use App\Utils\SessionUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EtatRepository;

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
     * @Route("/etats", name="etats")
     */
    public function index()
    {
        return $this->render('etats/index.html.twig', [
            'controller_name' => 'EtatsController',
        ]);
    }
    /**
     * @Route("/etats/transfert1", name="etats_rapport_transfert1", methods="GET|POST|UPDATE")
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
     * @Route("/etats/transfert", name="etats_rapport_transfert", methods="GET|POST|UPDATE")
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
            //dump($date); die();
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
     * @Route("/etats/transfert/apercue", name="etats_rapport_transfert_apercu", methods="GET|POST|UPDATE")
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
     * @Route("/etats/devises", name="etats_rapport_devises")
     */
    public function devises()
    {
        $date = new \DateTime();
        $em = $this->getDoctrine();
        $etatDevise = $em->getRepository(DeviseJournees::class)->trouverDevise($date);
        return $this->render('etats/devises.html.twig', [
            'etat' => $etatDevise,
        ]);
    }
}
