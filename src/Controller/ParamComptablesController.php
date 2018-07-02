<?php

namespace App\Controller;

use App\Entity\ParamComptables;
use App\Form\ParamComptablesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/param/comptables")
 */
class ParamComptablesController extends Controller
{
    /**
     * @Route("/", name="param_comptables_index", methods="GET")
     */
    public function index(): Response
    {
        $paramComptables = $this->getDoctrine()
            ->getRepository(ParamComptables::class)
            ->findAll();

        return $this->render('param_comptables/index.html.twig', ['param_comptables' => $paramComptables]);
    }

    /**
     * @Route("/new", name="param_comptables_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $paramComptable = new ParamComptables();
        $form = $this->createForm(ParamComptablesType::class, $paramComptable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($paramComptable);
            $em->flush();

            return $this->redirectToRoute('param_comptables_index');
        }

        return $this->render('param_comptables/new.html.twig', [
            'param_comptable' => $paramComptable,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="param_comptables_show", methods="GET")
     */
    public function show(ParamComptables $paramComptable): Response
    {
        return $this->render('param_comptables/show.html.twig', ['param_comptable' => $paramComptable]);
    }

    /**
     * @Route("/{id}/edit", name="param_comptables_edit", methods="GET|POST")
     */
    public function edit(Request $request, ParamComptables $paramComptable): Response
    {
        $form = $this->createForm(ParamComptablesType::class, $paramComptable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('param_comptables_edit', ['id' => $paramComptable->getId()]);
        }

        return $this->render('param_comptables/edit.html.twig', [
            'param_comptable' => $paramComptable,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="param_comptables_delete", methods="DELETE")
     */
    public function delete(Request $request, ParamComptables $paramComptable): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paramComptable->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($paramComptable);
            $em->flush();
        }

        return $this->redirectToRoute('param_comptables_index');
    }
}
