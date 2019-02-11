<?php

namespace App\Controller;

use App\Entity\DepotRetraits;
use App\Form\DepotRetraitsType;
use App\Repository\DepotRetraitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/depotRetraits")
 */
class DepotRetraitsController extends Controller
{
    /**
     * @Route("/", name="depot_retraits_index", methods="GET")
     */
    public function index(DepotRetraitsRepository $depotRetraitsRepository): Response
    {
        return $this->render('depot_retraits/index.html.twig', ['depot_retraits' => $depotRetraitsRepository->findAll()]);
    }

    /**
     * @Route("/new", name="depot_retraits_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $depotRetrait = new DepotRetraits();
        $form = $this->createForm(DepotRetraitsType::class, $depotRetrait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($depotRetrait);
            $em->flush();

            return $this->redirectToRoute('depot_retraits_index');
        }

        return $this->render('depot_retraits/new.html.twig', [
            'depot_retrait' => $depotRetrait,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="depot_retraits_show", methods="GET")
     */
    public function show(DepotRetraits $depotRetrait): Response
    {
        return $this->render('depot_retraits/show.html.twig', ['depot_retrait' => $depotRetrait]);
    }

    /**
     * @Route("/{id}/edit", name="depot_retraits_edit", methods="GET|POST")
     */
    public function edit(Request $request, DepotRetraits $depotRetrait): Response
    {
        $form = $this->createForm(DepotRetraitsType::class, $depotRetrait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('depot_retraits_index', ['id' => $depotRetrait->getId()]);
        }

        return $this->render('depot_retraits/edit.html.twig', [
            'depot_retrait' => $depotRetrait,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="depot_retraits_delete", methods="DELETE")
     */
    public function delete(Request $request, DepotRetraits $depotRetrait): Response
    {
        if ($this->isCsrfTokenValid('delete'.$depotRetrait->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($depotRetrait);
            $em->flush();
        }

        return $this->redirectToRoute('depot_retraits_index');
    }
}
