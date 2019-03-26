<?php

namespace App\Controller;

use App\Entity\CompenseLignes;
use App\Form\CompenseLignesType;
use App\Repository\CompenseLignesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/compense/lignes")
 */
class CompenseLignesController extends Controller
{
    /**
     * @Route("/", name="compense_lignes_index", methods="GET")
     */
    public function index(CompenseLignesRepository $compenseLignesRepository): Response
    {
        return $this->render('compense_lignes/index.html.twig', ['compense_lignes' => $compenseLignesRepository->findAll()]);
    }

    /**
     * @Route("/new", name="compense_lignes_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $compenseLigne = new CompenseLignes();
        $form = $this->createForm(CompenseLignesType::class, $compenseLigne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($compenseLigne);
            $em->flush();

            return $this->redirectToRoute('compense_lignes_index');
        }

        return $this->render('compense_lignes/new.html.twig', [
            'compense_ligne' => $compenseLigne,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="compense_lignes_show", methods="GET")
     */
    public function show(CompenseLignes $compenseLigne): Response
    {
        return $this->render('compense_lignes/show.html.twig', ['compense_ligne' => $compenseLigne]);
    }

    /**
     * @Route("/{id}/edit", name="compense_lignes_edit", methods="GET|POST")
     */
    public function edit(Request $request, CompenseLignes $compenseLigne): Response
    {
        $form = $this->createForm(CompenseLignesType::class, $compenseLigne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('compense_lignes_index', ['id' => $compenseLigne->getId()]);
        }

        return $this->render('compense_lignes/edit.html.twig', [
            'compense_ligne' => $compenseLigne,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="compense_lignes_delete", methods="DELETE")
     */
    public function delete(Request $request, CompenseLignes $compenseLigne): Response
    {
        if ($this->isCsrfTokenValid('delete'.$compenseLigne->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($compenseLigne);
            $em->flush();
        }

        return $this->redirectToRoute('compense_lignes_index');
    }
}
