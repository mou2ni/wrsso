<?php

namespace App\Controller;

use App\Entity\Salaires;
use App\Form\Salaires1Type;
use App\Repository\SalairesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/salaires")
 */
class SalairesController extends Controller
{
    /**
     * @Route("/", name="salaires_index", methods="GET")
     */
    public function index(SalairesRepository $salairesRepository): Response
    {
        return $this->render('salaires/index.html.twig', ['salaires' => $salairesRepository->findAll()]);
    }

    /**
     * @Route("/new", name="salaires_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $salaire = new Salaires();
        $form = $this->createForm(Salaires1Type::class, $salaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($salaire);
            $em->flush();

            return $this->redirectToRoute('salaires_index');
        }

        return $this->render('salaires/new.html.twig', [
            'salaire' => $salaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="salaires_show", methods="GET")
     */
    public function show(Salaires $salaire): Response
    {
        return $this->render('salaires/show.html.twig', ['salaire' => $salaire]);
    }

    /**
     * @Route("/{id}/edit", name="salaires_edit", methods="GET|POST")
     */
    public function edit(Request $request, Salaires $salaire): Response
    {
        $form = $this->createForm(Salaires1Type::class, $salaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('salaires_index', ['id' => $salaire->getId()]);
        }

        return $this->render('salaires/edit.html.twig', [
            'salaire' => $salaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="salaires_delete", methods="DELETE")
     */
    public function delete(Request $request, Salaires $salaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$salaire->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($salaire);
            $em->flush();
        }

        return $this->redirectToRoute('salaires_index');
    }
}
