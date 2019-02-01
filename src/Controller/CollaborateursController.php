<?php

namespace App\Controller;

use App\Entity\Collaborateurs;
use App\Form\CollaborateursType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/collaborateurs")
 */
class CollaborateursController extends Controller
{
    /**
     * @Route("/", name="collaborateurs_index", methods="GET")
     */
    public function index(): Response
    {
        $collaborateurs = $this->getDoctrine()
            ->getRepository(Collaborateurs::class)
            ->findAll();

        return $this->render('collaborateurs/index.html.twig', ['collaborateurs' => $collaborateurs]);
    }

    /**
     * @Route("/new", name="collaborateurs_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $collaborateur = new Collaborateurs();
        $form = $this->createForm(CollaborateursType::class, $collaborateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($collaborateur);
            $em->flush();

            return $this->redirectToRoute('collaborateurs_index');
        }

        return $this->render('collaborateurs/new.html.twig', [
            'collaborateur' => $collaborateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="collaborateurs_show", methods="GET")
     */
    public function show(Collaborateurs $collaborateur): Response
    {
        return $this->render('collaborateurs/show.html.twig', ['collaborateur' => $collaborateur]);
    }

    /**
     * @Route("/{id}/edit", name="collaborateurs_edit", methods="GET|POST")
     */
    public function edit(Request $request, Collaborateurs $collaborateur): Response
    {
        $form = $this->createForm(CollaborateursType::class, $collaborateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('collaborateurs_index', ['id' => $collaborateur->getId()]);
        }

        return $this->render('collaborateurs/edit.html.twig', [
            'collaborateur' => $collaborateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="collaborateurs_delete", methods="DELETE")
     */
    public function delete(Request $request, Collaborateurs $collaborateur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$collaborateur->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($collaborateur);
            $em->flush();
        }

        return $this->redirectToRoute('collaborateurs_index');
    }
}
