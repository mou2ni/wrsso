<?php

namespace App\Controller;

use App\Entity\TypeOperationComptables;
use App\Form\TypeOperationComptablesType;
use App\Repository\TypeOperationComptablesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/typeoperationcomptables")
 */
class TypeOperationComptablesController extends Controller
{
    /**
     * @Route("/", name="type_operation_comptables_index", methods="GET")
     */
    public function index(TypeOperationComptablesRepository $typeOperationComptable): Response
    {
        return $this->render('type_operation_comptables/index.html.twig', ['type_operation_comptables' => $typeOperationComptable->findAll()]);
    }

    /**
     * @Route("/new", name="type_operation_comptables_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $typeOperationComptable = new TypeOperationComptables();
        $form = $this->createForm(TypeOperationComptablesType::class, $typeOperationComptable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typeOperationComptable);
            $em->flush();

            return $this->redirectToRoute('type_operation_comptables_index');
        }

        return $this->render('type_operation_comptables/new.html.twig', [
            'type_operation_comptable' => $typeOperationComptable,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_operation_comptables_show", methods="GET")
     */
    public function show(TypeOperationComptables $typeOperationComptable): Response
    {
        return $this->render('type_operation_comptables/show.html.twig', ['type_operation_comptable' => $typeOperationComptable]);
    }

    /**
     * @Route("/{id}/edit", name="type_operation_comptables_edit", methods="GET|POST")
     */
    public function edit(Request $request, TypeOperationComptables $typeOperationComptable): Response
    {
        $form = $this->createForm(TypeOperationComptablesType::class, $typeOperationComptable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('type_operation_comptables_index', ['id' => $typeOperationComptable->getId()]);
        }

        return $this->render('type_operation_comptables/edit.html.twig', [
            'type_operation_comptable' => $typeOperationComptable,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_operation_comptables_delete", methods="DELETE")
     */
    public function delete(Request $request, TypeOperationComptables $typeOperationComptable): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeOperationComptable->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeOperationComptable);
            $em->flush();
        }

        return $this->redirectToRoute('type_operation_comptables_index');
    }
}
