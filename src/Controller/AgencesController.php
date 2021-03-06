<?php

namespace App\Controller;

use App\Entity\Agences;
use App\Form\AgencesType;
use App\Repository\AgencesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/agences")
 */
class AgencesController extends Controller
{
    /**
     * @Route("/", name="agences_index", methods="GET")
     */
    public function index(AgencesRepository $agencesRepository): Response
    {
        return $this->render('agences/index.html.twig', ['agences' => $agencesRepository->findAll()]);
    }

    /**
     * @Route("/new", name="agences_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $agence = new Agences();
        $form = $this->createForm(AgencesType::class, $agence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($agence);
            $em->flush();

            return $this->redirectToRoute('agences_index');
        }

        return $this->render('agences/new.html.twig', [
            'agence' => $agence,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="agences_show", methods="GET")
     */
    public function show(Agences $agence): Response
    {
        return $this->render('agences/show.html.twig', ['agence' => $agence]);
    }

    /**
     * @Route("/{id}/edit", name="agences_edit", methods="GET|POST")
     */
    public function edit(Request $request, Agences $agence): Response
    {
        $form = $this->createForm(AgencesType::class, $agence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('agences_index', ['id' => $agence->getId()]);
        }

        return $this->render('agences/edit.html.twig', [
            'agence' => $agence,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="agences_delete", methods="DELETE")
     */
    public function delete(Request $request, Agences $agence): Response
    {
        if ($this->isCsrfTokenValid('delete'.$agence->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($agence);
            $em->flush();
        }

        return $this->redirectToRoute('agences_index');
    }
}
