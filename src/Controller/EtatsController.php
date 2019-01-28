<?php

namespace App\Controller;

use App\Entity\DeviseJournees;
use App\Entity\SystemTransfert;
use App\Entity\TransfertInternationaux;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EtatRepository;

class EtatsController extends Controller
{
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
     * @Route("/etats/transfert", name="etats_rapport_transfert")
     */
    public function transfert()
    {
        $date = new \DateTime();
        $dateDeb=new \DateTime('2019-01-01 00:00:00');
        $dateFin=new \DateTime('2019-01-01 00:00:00');
        $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('1'));
        $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('t'));
        $em = $this->getDoctrine();
        $etatTransfert = $em->getRepository(TransfertInternationaux::class)->trouverTransfert($date);
        $etatTransfertTypeZone = $em->getRepository(TransfertInternationaux::class)->trouverTransfertTypeZone($date);
        $etatTransfertType = $em->getRepository(TransfertInternationaux::class)->trouverTransfertType($date);
        //dump($etatTransfertType);die();
        //dump($etatTransfert);die();
        return $this->render('etats/transfert.html.twig', [
            'etat' => $etatTransfert,
            'etatTypeZone' => $etatTransfertTypeZone,
            'etatType' => $etatTransfertType,
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
