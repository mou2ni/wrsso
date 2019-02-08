<?php

namespace App\Controller;

use App\Entity\DeviseJournees;
use App\Entity\Entreprises;
use App\Entity\ParamComptables;
use App\Entity\SystemTransfert;
use App\Entity\TransfertInternationaux;
use App\Repository\EntreprisesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/etats/transfert", name="etats_rapport_transfert", methods="GET|POST|UPDATE")
     */
    public function transfert(Request $request): Response
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
            $etatTransfert = $em->getRepository(TransfertInternationaux::class)->trouverTransfert($date);
        $etatTransfertTypeZone = $em->getRepository(TransfertInternationaux::class)->trouverTransfertTypeZone($date);
        $etatTransfertType = $em->getRepository(TransfertInternationaux::class)->trouverTransfertType($date);
        $entreprise = $em->getRepository(Entreprises::class)->findDetailsEntreprise();
        $dateDeb=new \DateTime('2019-01-01 00:00:00');
        $dateFin=new \DateTime('2019-01-01 00:00:00');
        $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('1'));
        $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('t'));
        return $this->render('etats/transfert.html.twig', [
            'etat' => $etatTransfert,
            'form' => $form->createView(),
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
