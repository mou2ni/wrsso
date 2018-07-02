<?php

namespace App\Controller;

use App\Entity\DeviseMouvements;
use App\Form\DeviseMouvementsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/devise/mouvements")
 */
class DeviseMouvementsController extends Controller
{
    /**
     * @Route("/", name="devise_mouvements_index", methods="GET")
     */
    public function index(): Response
    {
        $deviseMouvements = $this->getDoctrine()
            ->getRepository(DeviseMouvements::class)
            ->findAll();

        return $this->render('devise_mouvements/index.html.twig', ['devise_mouvements' => $deviseMouvements]);
    }

    /**
     * @Route("/new", name="devise_mouvements_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $deviseMouvement = new DeviseMouvements();
        $form = $this->createForm(DeviseMouvementsType::class, $deviseMouvement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($deviseMouvement);
            $em->flush();

            return $this->redirectToRoute('devise_mouvements_index');
        }

        return $this->render('devise_mouvements/new.html.twig', [
            'devise_mouvement' => $deviseMouvement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="devise_mouvements_show", methods="GET")
     */
    public function show(DeviseMouvements $deviseMouvement): Response
    {
        return $this->render('devise_mouvements/show.html.twig', ['devise_mouvement' => $deviseMouvement]);
    }

    /**
     * @Route("/{id}/edit", name="devise_mouvements_edit", methods="GET|POST")
     */
    public function edit(Request $request, DeviseMouvements $deviseMouvement): Response
    {
        $form = $this->createForm(DeviseMouvementsType::class, $deviseMouvement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('devise_mouvements_edit', ['id' => $deviseMouvement->getId()]);
        }

        return $this->render('devise_mouvements/edit.html.twig', [
            'devise_mouvement' => $deviseMouvement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="devise_mouvements_delete", methods="DELETE")
     */
    public function delete(Request $request, DeviseMouvements $deviseMouvement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$deviseMouvement->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($deviseMouvement);
            $em->flush();
        }

        return $this->redirectToRoute('devise_mouvements_index');
    }
}
