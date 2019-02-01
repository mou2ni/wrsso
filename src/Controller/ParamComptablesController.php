<?php

namespace App\Controller;

use App\Entity\ParamComptables;
use App\Form\ParamComptablesType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function new(Request $request): Response
    {
        $paramComptable = new ParamComptables();
        $form = $this->createForm(ParamComptablesType::class, $paramComptable);
        $form->handleRequest($request);
        $paramComptables = $this->getDoctrine()
            ->getRepository(ParamComptables::class)
            ->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($paramComptable);
            $em->flush();

            return $this->redirectToRoute('param_comptables_index');
        }

        return $this->render('param_comptables/new.html.twig', [
            'param_comptable' => $paramComptable,
            'form' => $form->createView(),
            'param_comptables' => $paramComptables
        ]);
    }

    /**
     * @Route("/{id}", name="param_comptables_show", methods="GET")
     */
    public function show(ParamComptables $paramComptable): Response
    {
        $paramComptables = $this->getDoctrine()
        ->getRepository(ParamComptables::class)
        ->findAll();
        return $this->render('param_comptables/show.html.twig', ['param_comptable' => $paramComptable,'param_comptables' => $paramComptables]);
    }

    /**
     * @Route("/{id}/edit", name="param_comptables_edit", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function edit(Request $request, ParamComptables $paramComptable): Response
    {
        $paramComptables = $this->getDoctrine()
            ->getRepository(ParamComptables::class)
            ->findAll();
        $form = $this->createForm(ParamComptablesType::class, $paramComptable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('param_comptables_edit', ['id' => $paramComptable->getId()]);
        }

        return $this->render('param_comptables/edit.html.twig', [
            'param_comptable' => $paramComptable,
            'form' => $form->createView(),
            'param_comptables' => $paramComptables
        ]);
    }

    /**
     * @Route("/{id}", name="param_comptables_delete", methods="DELETE")
     * @Security("has_role('ROLE_COMPTABLE')")
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
