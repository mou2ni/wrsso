<?php

namespace App\Controller;

use App\Entity\SystemTransfert;
use App\Form\SystemTransfertType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/systemtransfert")
 */
class SystemTransfertController extends Controller
{
    /**
     * @Route("/", name="system_transfert_index", methods="GET")
     */
    public function index(): Response
    {
        $systemTransferts = $this->getDoctrine()
            ->getRepository(SystemTransfert::class)
            ->findAll();

        return $this->render('system_transfert/index.html.twig', ['system_transferts' => $systemTransferts]);
    }

    /**
     * @Route("/new", name="system_transfert_new", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function new(Request $request): Response
    {
        $systemTransfert = new SystemTransfert();
        $form = $this->createForm(SystemTransfertType::class, $systemTransfert);
        $form->handleRequest($request);
        $systemTransferts = $this->getDoctrine()
            ->getRepository(SystemTransfert::class)
            ->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($systemTransfert);
            $em->flush();

            return $this->redirectToRoute('system_transfert_index');
        }

        return $this->render('system_transfert/new.html.twig', [
            'system_transfert' => $systemTransfert,
            'form' => $form->createView(),
            'system_transferts' => $systemTransferts
        ]);
    }

    /**
     * @Route("/{id}", name="system_transfert_show", methods="GET")
     */
    public function show(SystemTransfert $systemTransfert): Response
    {
        $systemTransferts = $this->getDoctrine()
            ->getRepository(SystemTransfert::class)
            ->findAll();
        return $this->render('system_transfert/show.html.twig', ['system_transfert' => $systemTransfert, 'system_transferts' => $systemTransferts]);
    }

    /**
     * @Route("/{id}/edit", name="system_transfert_edit", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function edit(Request $request, SystemTransfert $systemTransfert): Response
    {
        $form = $this->createForm(SystemTransfertType::class, $systemTransfert);
        $form->handleRequest($request);
        $systemTransferts = $this->getDoctrine()
            ->getRepository(SystemTransfert::class)
            ->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('system_transfert_edit', ['id' => $systemTransfert->getId()]);
        }

        return $this->render('system_transfert/edit.html.twig', [
            'system_transfert' => $systemTransfert,
            'form' => $form->createView(),
            'system_transferts' => $systemTransferts
        ]);
    }

    /**
     * @Route("/{id}", name="system_transfert_delete", methods="DELETE")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function delete(Request $request, SystemTransfert $systemTransfert): Response
    {
        if ($this->isCsrfTokenValid('delete'.$systemTransfert->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($systemTransfert);
            $em->flush();
        }

        return $this->redirectToRoute('system_transfert_index');
    }
}
