<?php

namespace App\Controller;

use App\Entity\DetteCreditDivers;
use App\Entity\JourneeCaisses;
use App\Form\DetteCreditDiversType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dette/credit/divers")
 */
class DetteCreditDiversController extends Controller
{
    /**
     * @Route("/", name="dette_credit_divers_index", methods="GET")
     */
    public function index(): Response
    {
        $detteCreditDivers = $this->getDoctrine()
            ->getRepository(DetteCreditDivers::class)
            ->findAll();

        return $this->render('dette_credit_divers/index.html.twig', ['dette_credit_divers' => $detteCreditDivers]);
    }

    /**
     * @Route("/ajout/{id}", name="dette_credit_divers", methods="GET|POST|UPDATE")
     */
    public function ajout(Request $request, JourneeCaisses $journeeCaisse): Response
    {
        $em = $this->getDoctrine()->getManager();
        $operation=$request->request->get('_operation');
        $detteCredit = new DetteCreditDivers($journeeCaisse);
        $form = $this->createForm(DetteCreditDiversType::class, $detteCredit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($request->request->has('fermer')){
                return $this->redirectToRoute('journee_caisses_gerer');
            }

            $journeeCaisse->addDetteCredit($detteCredit);
            /*if ($operation=='OUVRIR'){
                $journeeCaisse->setMCreditDiversOuv($journeeCaisse->getMCreditDiversOuv() + $detteCredit->getMCredit());
                $journeeCaisse->setMDetteDiversOuv($journeeCaisse->getMDetteDiversOuv() + $detteCredit->getMDette());
            }
            elseif ($operation=='FERMER'){*/
                $journeeCaisse->setMCreditDiversFerm($this->getTotalCredits($journeeCaisse));
                $journeeCaisse->setMDetteDiversFerm($this->getTotalDettes($journeeCaisse));
            //dump($journeeCaisse);die();
            //}
            $em->persist($journeeCaisse);
            $em->flush();

            return $this->redirectToRoute('dette_credit_divers',['id'=>$journeeCaisse->getId()]);
        }

        return $this->render('dette_credit_divers/ajout.html.twig', [
            'journeeCaisse'=>$journeeCaisse,
            //'detteCredit' => $detteCredit,
            'form' => $form->createView(),
            'operation'=>$operation
        ]);
    }

    /**
     * @Route("/new", name="dette_credit_divers_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $detteCreditDiver = new DetteCreditDivers();
        $form = $this->createForm(DetteCreditDiversType::class, $detteCreditDiver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($detteCreditDiver);
            $em->flush();

            return $this->redirectToRoute('dette_credit_divers_index');
        }

        return $this->render('dette_credit_divers/new.html.twig', [
            'dette_credit_diver' => $detteCreditDiver,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dette_credit_divers_show", methods="GET")
     */
    public function show(DetteCreditDivers $detteCreditDiver): Response
    {
        return $this->render('dette_credit_divers/show.html.twig', ['dette_credit_diver' => $detteCreditDiver]);
    }

    /**
     * @Route("/{id}/edit", name="dette_credit_divers_edit", methods="GET|POST")
     */
    public function edit(Request $request, DetteCreditDivers $detteCreditDiver): Response
    {
        $form = $this->createForm(DetteCreditDiversType::class, $detteCreditDiver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dette_credit_divers_edit', ['id' => $detteCreditDiver->getId()]);
        }

        return $this->render('dette_credit_divers/edit.html.twig', [
            'dette_credit_diver' => $detteCreditDiver,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dette_credit_divers_delete", methods="DELETE")
     */
    public function delete(Request $request, DetteCreditDivers $detteCreditDiver): Response
    {
        if ($this->isCsrfTokenValid('delete'.$detteCreditDiver->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($detteCreditDiver);
            $em->flush();
        }

        return $this->redirectToRoute('dette_credit_divers_index');
    }

    public function getTotalDettes(JourneeCaisses $journeeCaisse){
        $total=0;
        foreach ($journeeCaisse->getDetteCredits() as $dc){
            $total=$total + $dc->getMDette();
        }
        return $total;
    }

    public function getTotalCredits(JourneeCaisses $journeeCaisse){
        $total=0;
        foreach ($journeeCaisse->getDetteCredits() as $dc){
            $total=$total + $dc->getMCredit();
        }
        return $total;
    }
}
