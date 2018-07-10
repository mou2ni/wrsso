<?php

namespace App\Controller;

use App\Entity\Caisses;
use App\Form\CaissesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/caisses")
 */
class CaissesController extends Controller
{
    /**
     * @Route("/", name="caisses_index", methods="GET")
     */
    public function index(): Response
    {
        $caisses = $this->getDoctrine()
            ->getRepository(Caisses::class)
            ->findAll();

        return $this->render('caisses/index.html.twig', ['caisses' => $caisses]);
    }

    /**
     * @Route("/new", name="caisses_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $caiss = new Caisses();
        $form = $this->createForm(CaissesType::class, $caiss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($caiss);
            $em->flush();

            return $this->redirectToRoute('caisses_index');
        }

        return $this->render('caisses/new.html.twig', [
            'caiss' => $caiss,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="caisses_show", methods="GET")
     */
    public function show(Caisses $caiss): Response
    {
        return $this->render('caisses/show.html.twig', ['caiss' => $caiss]);
    }

    /**
     * @Route("/{id}/edit", name="caisses_edit", methods="GET|POST")
     */
    public function edit(Request $request, Caisses $caiss): Response
    {
        $form = $this->createForm(CaissesType::class, $caiss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('caisses_edit', ['id' => $caiss->getId()]);
        }

        return $this->render('caisses/edit.html.twig', [
            'caiss' => $caiss,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="caisses_delete", methods="DELETE")
     */
    public function delete(Request $request, Caisses $caiss): Response
    {
        if ($this->isCsrfTokenValid('delete'.$caiss->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($caiss);
            $em->flush();
        }

        return $this->redirectToRoute('caisses_index');
    }
}