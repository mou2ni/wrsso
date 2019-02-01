<?php

namespace App\Controller;

use App\Entity\Entreprises;
use App\Form\EntreprisesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/entreprises")
 */
class EntreprisesController extends Controller
{
    /**
     * @Route("/", name="entreprises_index", methods="GET")
     */
    public function index(): Response
    {
        $entreprises = $this->getDoctrine()
            ->getRepository(Entreprises::class)
            ->findAll();

        return $this->render('entreprises/index.html.twig', ['entreprises' => $entreprises]);
    }

    /**
     * @Route("/new", name="entreprises_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $entreprise = new Entreprises();
        $form = $this->createForm(EntreprisesType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entreprise);
            $em->flush();

            return $this->redirectToRoute('entreprises_index');
        }

        return $this->render('entreprises/new.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entreprises_show", methods="GET")
     */
    public function show(Entreprises $entreprise): Response
    {
        return $this->render('entreprises/show.html.twig', ['entreprise' => $entreprise]);
    }

    /**
     * @Route("/{id}/edit", name="entreprises_edit", methods="GET|POST")
     */
    public function edit(Request $request, Entreprises $entreprise): Response
    {
        $form = $this->createForm(EntreprisesType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('entreprises_index', ['id' => $entreprise->getId()]);
        }

        return $this->render('entreprises/edit.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entreprises_delete", methods="DELETE")
     */
    public function delete(Request $request, Entreprises $entreprise): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entreprise->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($entreprise);
            $em->flush();
        }

        return $this->redirectToRoute('entreprises_index');
    }
}
