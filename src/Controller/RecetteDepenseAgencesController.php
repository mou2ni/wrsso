<?php

namespace App\Controller;

use App\Entity\RecetteDepenseAgences;
use App\Form\RecetteDepenseAgencesType;
use App\Repository\RecetteDepenseAgencesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/recette/depense/agences")
 */
class RecetteDepenseAgencesController extends Controller
{
    /**
     * @Route("/", name="recette_depense_agences_index", methods="GET")
     */
    public function index(RecetteDepenseAgencesRepository $recetteDepenseAgencesRepository): Response
    {
        return $this->render('recette_depense_agences/index.html.twig', ['recette_depense_agences' => $recetteDepenseAgencesRepository->findAll()]);
    }

    /**
     * @Route("/new", name="recette_depense_agences_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $recetteDepenseAgence = new RecetteDepenseAgences();
        $form = $this->createForm(RecetteDepenseAgencesType::class, $recetteDepenseAgence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($recetteDepenseAgence);
            $em->flush();

            return $this->redirectToRoute('recette_depense_agences_index');
        }

        return $this->render('recette_depense_agences/new.html.twig', [
            'recette_depense_agence' => $recetteDepenseAgence,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recette_depense_agences_show", methods="GET")
     */
    public function show(RecetteDepenseAgences $recetteDepenseAgence): Response
    {
        return $this->render('recette_depense_agences/show.html.twig', ['recette_depense_agence' => $recetteDepenseAgence]);
    }

    /**
     * @Route("/{id}/edit", name="recette_depense_agences_edit", methods="GET|POST")
     */
    public function edit(Request $request, RecetteDepenseAgences $recetteDepenseAgence): Response
    {
        $form = $this->createForm(RecetteDepenseAgencesType::class, $recetteDepenseAgence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recette_depense_agences_index', ['id' => $recetteDepenseAgence->getId()]);
        }

        return $this->render('recette_depense_agences/edit.html.twig', [
            'recette_depense_agence' => $recetteDepenseAgence,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recette_depense_agences_delete", methods="DELETE")
     */
    public function delete(Request $request, RecetteDepenseAgences $recetteDepenseAgence): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recetteDepenseAgence->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($recetteDepenseAgence);
            $em->flush();
        }

        return $this->redirectToRoute('recette_depense_agences_index');
    }
}
