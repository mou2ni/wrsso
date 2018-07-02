<?php

namespace App\Controller;

use App\Entity\Billets;
use App\Form\BilletsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/billets")
 */
class BilletsController extends Controller
{
    /**
     * @Route("/", name="billets_index", methods="GET")
     */
    public function index(): Response
    {
        $billets = $this->getDoctrine()
            ->getRepository(Billets::class)
            ->findAll();

        return $this->render('billets/index.html.twig', ['billets' => $billets]);
    }

    /**
     * @Route("/new", name="billets_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $billet = new Billets();
        $form = $this->createForm(BilletsType::class, $billet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($billet);
            $em->flush();

            return $this->redirectToRoute('billets_index');
        }

        return $this->render('billets/new.html.twig', [
            'billet' => $billet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="billets_show", methods="GET")
     */
    public function show(Billets $billet): Response
    {
        return $this->render('billets/show.html.twig', ['billet' => $billet]);
    }

    /**
     * @Route("/{id}/edit", name="billets_edit", methods="GET|POST")
     */
    public function edit(Request $request, Billets $billet): Response
    {
        $form = $this->createForm(BilletsType::class, $billet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('billets_edit', ['id' => $billet->getId()]);
        }

        return $this->render('billets/edit.html.twig', [
            'billet' => $billet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="billets_delete", methods="DELETE")
     */
    public function delete(Request $request, Billets $billet): Response
    {
        if ($this->isCsrfTokenValid('delete'.$billet->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($billet);
            $em->flush();
        }

        return $this->redirectToRoute('billets_index');
    }
}
