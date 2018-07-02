<?php

namespace App\Controller;

use App\Entity\DetteCreditDivers;
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
}
