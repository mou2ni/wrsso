<?php

namespace App\Controller;

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
     * @Route("/etats/transfert", name="etats_transfert")
     */
    public function transfert()
    {
        $date = new \DateTime();
        $date->setDate('2018','12','1');
        $em = $this->getDoctrine();
        $etatTransfert = $em->getRepository(TransfertInternationaux::class)->trouverTransfert($date);
        $etatTransfertTypeZone = $em->getRepository(TransfertInternationaux::class)->trouverTransfertTypeZone($date);
        $etatTransfertType = $em->getRepository(TransfertInternationaux::class)->trouverTransfertType($date);
        //$etat=$etatRepo->etatTransfertMois($date);
        //dump($etatTransfertType);die();
        return $this->render('etats/transfert.html.twig', [
            'etat' => $etatTransfert,
            'etatTypeZone' => $etatTransfertTypeZone,
            'etatType' => $etatTransfertType,
        ]);
    }
}
