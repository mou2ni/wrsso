<?php

namespace App\Controller;

use App\Entity\Devises;
use App\Form\DevisesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/devises")
 */
class DevisesController extends Controller
{
    /**
     * @Route("/", name="devises_index", methods="GET")
     */
    public function index(): Response
    {
        $devises = $this->getDoctrine()
            ->getRepository(Devises::class)
            ->findAll();

        return $this->render('devises/index.html.twig', ['devises' => $devises]);
    }

    /**
     * @Route("/new", name="devises_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $devise = new Devises();
        $form = $this->createForm(DevisesType::class, $devise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($devise);
            $em->flush();

            return $this->redirectToRoute('devises_index');
        }

        return $this->render('devises/new.html.twig', [
            'devise' => $devise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="devises_show", methods="GET")
     */
    public function show(Devises $devise): Response
    {
        return $this->render('devises/show.html.twig', ['devise' => $devise]);
    }

    /**
     * @Route("/{id}/edit", name="devises_edit", methods="GET|POST")
     */
    public function edit(Request $request, Devises $devise): Response
    {
        $form = $this->createForm(DevisesType::class, $devise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('devises_edit', ['id' => $devise->getId()]);
        }

        return $this->render('devises/edit.html.twig', [
            'devise' => $devise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="devises_delete", methods="DELETE")
     */
    public function delete(Request $request, Devises $devise): Response
    {
        if ($this->isCsrfTokenValid('delete'.$devise->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($devise);
            $em->flush();
        }

        return $this->redirectToRoute('devises_index');
    }
}
