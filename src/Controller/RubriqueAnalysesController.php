<?php

namespace App\Controller;

use App\Entity\RubriqueAnalyses;
use App\Form\RubriqueAnalysesType;
use App\Repository\RubriqueAnalysesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rubrique/analyses")
 */
class RubriqueAnalysesController extends Controller
{
    /**
     * @Route("/", name="rubrique_analyses_index", methods="GET")
     */
    public function index(RubriqueAnalysesRepository $rubriqueAnalysesRepository): Response
    {
        return $this->render('rubrique_analyses/index.html.twig', ['rubrique_analyses' => $rubriqueAnalysesRepository->findAll()]);
    }

    /**
     * @Route("/new", name="rubrique_analyses_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $rubriqueAnalysis = new RubriqueAnalyses();
        $form = $this->createForm(RubriqueAnalysesType::class, $rubriqueAnalysis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($rubriqueAnalysis);
            $em->flush();

            return $this->redirectToRoute('rubrique_analyses_index');
        }

        return $this->render('rubrique_analyses/new.html.twig', [
            'rubrique_analysis' => $rubriqueAnalysis,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rubrique_analyses_show", methods="GET")
     */
    public function show(RubriqueAnalyses $rubriqueAnalysis): Response
    {
        return $this->render('rubrique_analyses/show.html.twig', ['rubrique_analysis' => $rubriqueAnalysis]);
    }

    /**
     * @Route("/{id}/edit", name="rubrique_analyses_edit", methods="GET|POST")
     */
    public function edit(Request $request, RubriqueAnalyses $rubriqueAnalysis): Response
    {
        $form = $this->createForm(RubriqueAnalysesType::class, $rubriqueAnalysis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rubrique_analyses_index', ['id' => $rubriqueAnalysis->getId()]);
        }

        return $this->render('rubrique_analyses/edit.html.twig', [
            'rubrique_analysis' => $rubriqueAnalysis,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rubrique_analyses_delete", methods="DELETE")
     */
    public function delete(Request $request, RubriqueAnalyses $rubriqueAnalysis): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rubriqueAnalysis->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($rubriqueAnalysis);
            $em->flush();
        }

        return $this->redirectToRoute('rubrique_analyses_index');
    }
}
