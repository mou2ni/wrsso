<?php

namespace App\Controller;

use App\Entity\DeviseJournees;
use App\Form\DeviseJourneesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/devise/journees")
 */
class DeviseJourneesController extends Controller
{
    /**
     * @Route("/", name="devise_journees_index", methods="GET")
     */
    public function index(): Response
    {
        $deviseJournees = $this->getDoctrine()
            ->getRepository(DeviseJournees::class)
            ->findAll();

        return $this->render('devise_journees/index.html.twig', ['devise_journees' => $deviseJournees]);
    }

    /**
     * @Route("/new", name="devise_journees_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $deviseJournee = new DeviseJournees();
        $form = $this->createForm(DeviseJourneesType::class, $deviseJournee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($deviseJournee);
            $em->flush();

            return $this->redirectToRoute('devise_journees_index');
        }

        return $this->render('devise_journees/new.html.twig', [
            'devise_journee' => $deviseJournee,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="devise_journees_show", methods="GET")
     */
    public function show(DeviseJournees $deviseJournee): Response
    {
        return $this->render('devise_journees/show.html.twig', ['devise_journee' => $deviseJournee]);
    }

    /**
     * @Route("/{id}/edit", name="devise_journees_edit", methods="GET|POST")
     */
    public function edit(Request $request, DeviseJournees $deviseJournee): Response
    {
        $form = $this->createForm(DeviseJourneesType::class, $deviseJournee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('devise_journees_edit', ['id' => $deviseJournee->getId()]);
        }

        return $this->render('devise_journees/edit.html.twig', [
            'devise_journee' => $deviseJournee,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="devise_journees_delete", methods="DELETE")
     */
    public function delete(Request $request, DeviseJournees $deviseJournee): Response
    {
        if ($this->isCsrfTokenValid('delete'.$deviseJournee->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($deviseJournee);
            $em->flush();
        }

        return $this->redirectToRoute('devise_journees_index');
    }
}
