<?php

namespace App\Controller;

use App\Entity\RecetteDepenses;
use App\Form\RecetteDepensesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/recette/depenses")
 */
class RecetteDepensesController extends Controller
{
    /**
     * @Route("/", name="recette_depenses_index", methods="GET")
     */
    public function index(): Response
    {
        $recetteDepenses = $this->getDoctrine()
            ->getRepository(RecetteDepenses::class)
            ->findAll();

        return $this->render('recette_depenses/index.html.twig', ['recette_depenses' => $recetteDepenses]);
    }

    /**
     * @Route("/new", name="recette_depenses_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $recetteDepense = new RecetteDepenses();
        $form = $this->createForm(RecetteDepensesType::class, $recetteDepense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($recetteDepense);
            $em->flush();

            return $this->redirectToRoute('recette_depenses_index');
        }

        return $this->render('recette_depenses/new.html.twig', [
            'recette_depense' => $recetteDepense,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recette_depenses_show", methods="GET")
     */
    public function show(RecetteDepenses $recetteDepense): Response
    {
        return $this->render('recette_depenses/show.html.twig', ['recette_depense' => $recetteDepense]);
    }

    /**
     * @Route("/{id}/edit", name="recette_depenses_edit", methods="GET|POST")
     */
    public function edit(Request $request, RecetteDepenses $recetteDepense): Response
    {
        $form = $this->createForm(RecetteDepensesType::class, $recetteDepense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recette_depenses_edit', ['id' => $recetteDepense->getId()]);
        }

        return $this->render('recette_depenses/edit.html.twig', [
            'recette_depense' => $recetteDepense,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recette_depenses_delete", methods="DELETE")
     */
    public function delete(Request $request, RecetteDepenses $recetteDepense): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recetteDepense->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($recetteDepense);
            $em->flush();
        }

        return $this->redirectToRoute('recette_depenses_index');
    }
}
