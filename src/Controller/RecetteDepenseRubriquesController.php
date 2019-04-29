<?php

namespace App\Controller;

use App\Entity\RecetteDepenseRubriques;
use App\Form\RecetteDepenseRubriquesType;
use App\Repository\RecetteDepenseRubriquesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/recette/depense/rubriques")
 */
class RecetteDepenseRubriquesController extends Controller
{
    /**
     * @Route("/", name="recette_depense_rubriques_index", methods="GET")
     */
    public function index(RecetteDepenseRubriquesRepository $recetteDepenseRubriquesRepository): Response
    {
        return $this->render('recette_depense_rubriques/index.html.twig', ['recette_depense_rubriques' => $recetteDepenseRubriquesRepository->findAll()]);
    }

    /**
     * @Route("/new", name="recette_depense_rubriques_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $recetteDepenseRubrique = new RecetteDepenseRubriques();
        $form = $this->createForm(RecetteDepenseRubriquesType::class, $recetteDepenseRubrique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($recetteDepenseRubrique);
            $em->flush();

            return $this->redirectToRoute('recette_depense_rubriques_index');
        }

        return $this->render('recette_depense_rubriques/new.html.twig', [
            'recette_depense_rubrique' => $recetteDepenseRubrique,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recette_depense_rubriques_show", methods="GET")
     */
    public function show(RecetteDepenseRubriques $recetteDepenseRubrique): Response
    {
        return $this->render('recette_depense_rubriques/show.html.twig', ['recette_depense_rubrique' => $recetteDepenseRubrique]);
    }

    /**
     * @Route("/{id}/edit", name="recette_depense_rubriques_edit", methods="GET|POST")
     */
    public function edit(Request $request, RecetteDepenseRubriques $recetteDepenseRubrique): Response
    {
        $form = $this->createForm(RecetteDepenseRubriquesType::class, $recetteDepenseRubrique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recette_depense_rubriques_index', ['id' => $recetteDepenseRubrique->getId()]);
        }

        return $this->render('recette_depense_rubriques/edit.html.twig', [
            'recette_depense_rubrique' => $recetteDepenseRubrique,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recette_depense_rubriques_delete", methods="DELETE")
     */
    public function delete(Request $request, RecetteDepenseRubriques $recetteDepenseRubrique): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recetteDepenseRubrique->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($recetteDepenseRubrique);
            $em->flush();
        }

        return $this->redirectToRoute('recette_depense_rubriques_index');
    }
}
