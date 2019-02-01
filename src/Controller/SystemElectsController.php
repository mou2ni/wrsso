<?php

namespace App\Controller;

use App\Entity\SystemElects;
use App\Form\SystemElectsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/system/elects")
 */
class SystemElectsController extends Controller
{
    /**
     * @Route("/", name="system_elects_index", methods="GET")
     */
    public function index(): Response
    {
        $systemElects = $this->getDoctrine()
            ->getRepository(SystemElects::class)
            ->findAll();

        return $this->render('system_elects/index.html.twig', ['system_elects' => $systemElects]);
    }

    /**
     * @Route("/new", name="system_elects_new", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function new(Request $request): Response
    {
        $systemElect = new SystemElects();
        $form = $this->createForm(SystemElectsType::class, $systemElect);
        $form->handleRequest($request);
        $systemElects = $this->getDoctrine()
            ->getRepository(SystemElects::class)
            ->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($systemElect);
            $em->flush();

            return $this->redirectToRoute('system_elects_index');
        }

        return $this->render('system_elects/new.html.twig', [
            'system_elect' => $systemElect,
            'form' => $form->createView(),
            'system_elects' => $systemElects
        ]);
    }

    /**
     * @Route("/{id}", name="system_elects_show", methods="GET")
     */
    public function show(SystemElects $systemElect): Response
    {
        $systemElects = $this->getDoctrine()
            ->getRepository(SystemElects::class)
            ->findAll();
        return $this->render('system_elects/show.html.twig', ['system_elect' => $systemElect,'system_elects' => $systemElects]);
    }

    /**
     * @Route("/{id}/edit", name="system_elects_edit", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function edit(Request $request, SystemElects $systemElect): Response
    {
        $form = $this->createForm(SystemElectsType::class, $systemElect);
        $form->handleRequest($request);
        $systemElects = $this->getDoctrine()
            ->getRepository(SystemElects::class)
            ->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('system_elects_edit', ['id' => $systemElect->getId(),'system_elects' => $systemElects]);
        }

        return $this->render('system_elects/edit.html.twig', [
            'system_elect' => $systemElect,
            'form' => $form->createView(),
            'system_elects' => $systemElects
        ]);
    }

    /**
     * @Route("/{id}", name="system_elects_delete", methods="DELETE")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function delete(Request $request, SystemElects $systemElect): Response
    {
        if ($this->isCsrfTokenValid('delete'.$systemElect->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($systemElect);
            $em->flush();
        }

        return $this->redirectToRoute('system_elects_index');
    }
}
