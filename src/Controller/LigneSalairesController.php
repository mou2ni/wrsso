<?php

namespace App\Controller;

use App\Entity\LigneSalaires;
use App\Form\LigneSalairesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ligne/salaires")
 */
class LigneSalairesController extends Controller
{
    /**
     * @Route("/", name="ligne_salaires_index", methods="GET")
     */
    public function index(): Response
    {
        $ligneSalaires = $this->getDoctrine()
            ->getRepository(LigneSalaires::class)
            ->findAll();

        return $this->render('ligne_salaires/index.html.twig', ['ligne_salaires' => $ligneSalaires]);
    }

    /**
     * @Route("/new", name="ligne_salaires_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $ligneSalaire = new LigneSalaires();
        $form = $this->createForm(LigneSalairesType::class, $ligneSalaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ligneSalaire);
            $em->flush();

            return $this->redirectToRoute('ligne_salaires_index');
        }

        return $this->render('ligne_salaires/new.html.twig', [
            'ligne_salaire' => $ligneSalaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_salaires_show", methods="GET")
     */
    public function show(LigneSalaires $ligneSalaire): Response
    {
        return $this->render('ligne_salaires/show.html.twig', ['ligne_salaire' => $ligneSalaire]);
    }

    /**
     * @Route("/{id}/edit", name="ligne_salaires_edit", methods="GET|POST")
     */
    public function edit(Request $request, LigneSalaires $ligneSalaire): Response
    {
        $form = $this->createForm(LigneSalairesType::class, $ligneSalaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ligne_salaires_index', ['id' => $ligneSalaire->getId()]);
        }

        return $this->render('ligne_salaires/edit.html.twig', [
            'ligne_salaire' => $ligneSalaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_salaires_delete", methods="DELETE")
     */
    public function delete(Request $request, LigneSalaires $ligneSalaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ligneSalaire->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ligneSalaire);
            $em->flush();
        }

        return $this->redirectToRoute('ligne_salaires_index');
    }
}
