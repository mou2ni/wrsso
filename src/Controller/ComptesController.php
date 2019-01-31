<?php

namespace App\Controller;

use App\Entity\Comptes;
use App\Form\ComptesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/comptes")
 */
class ComptesController extends Controller
{
    /**
     * @Route("/", name="comptes_index", methods="GET")
     */
    public function index(): Response
    {
        $comptes = $this->getDoctrine()
            ->getRepository(Comptes::class)
            ->liste();

        return $this->render('comptes/index.html.twig', ['comptes' => $comptes]);
    }

    /**
     * @Route("/ajout", name="comptes_new", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function new(Request $request): Response
    {
        $compte = new Comptes();
        $form = $this->createForm(ComptesType::class, $compte);
        $form->handleRequest($request);
        $comptes = $this->getDoctrine()
            ->getRepository(Comptes::class)
            ->liste();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($compte);
            $em->flush();

            return $this->redirectToRoute('comptes_index');
        }

        return $this->render('comptes/new.html.twig', [
            'compte' => $compte,
            'form' => $form->createView(),
            'comptes' => $comptes
        ]);
    }

    /**
     * @Route("/{id}/detail", name="comptes_show", methods="GET")
     */
    public function show(Comptes $compte): Response
    {
        $comptes = $this->getDoctrine()
        ->getRepository(Comptes::class)
        ->liste();
        return $this->render('comptes/show.html.twig', ['compte' => $compte,
            'comptes' => $comptes]);
    }

    /**
     * @Route("/{id}/modifier", name="comptes_edit", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function edit(Request $request, Comptes $compte): Response
    {
        $form = $this->createForm(ComptesType::class, $compte);
        $form->handleRequest($request);
        $comptes = $this->getDoctrine()
            ->getRepository(Comptes::class)
            ->liste();
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comptes_edit', ['id' => $compte->getId()]);
        }

        return $this->render('comptes/edit.html.twig', [
            'compte' => $compte,
            'form' => $form->createView(),
            'comptes' => $comptes
        ]);
    }

    /**
     * @Route("/{id}", name="comptes_delete", methods="DELETE")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function delete(Request $request, Comptes $compte): Response
    {
        if ($this->isCsrfTokenValid('delete'.$compte->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($compte);
            $em->flush();
        }

        return $this->redirectToRoute('comptes_index');
    }
}
