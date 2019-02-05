<?php

namespace App\Controller;

use App\Entity\Collaborateurs;
use App\Entity\LigneSalaires;
use App\Entity\Salaires;
use App\Form\SalairesType;
use App\Repository\SalairesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/salaires")
 */
class SalairesController extends Controller
{
    /**
     * @Route("/", name="salaires_index", methods="GET")
     */
    public function index(SalairesRepository $salairesRepository): Response
    {
        return $this->render('salaires/index.html.twig', ['salaires' => $salairesRepository->findAll()]);
    }

    /**
     * @Route("/positionnement", name="salaires_positionnement", methods="GET|POST")
     */
    public function positionner(Request $request): Response
    {
        //$em=$this->getDoctrine()->getManager();
        //$salaire=$em->getRepository(Salaires::class)->findOneBy(['periodeSalaire'=>$periodeSalaire]);

        $collaborateurs=$this->getDoctrine()->getRepository(Collaborateurs::class)->findBy(['statut'=>Collaborateurs::STAT_SALARIE]);

        $salaire = new Salaires();
        $salaire->fillLigneSalaireFromCollaborateurs($collaborateurs);
        $salaire->setPeriodeSalaire(new \DateTime());
        $form = $this->createForm(SalairesType::class, $salaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($salaire);
            $em->flush();

            return $this->redirectToRoute('salaires_index');
        }

        return $this->render('salaires/positionnement.html.twig', [
            'salaire' => $salaire,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/new", name="salaires_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $salaire = new Salaires();
        $form = $this->createForm(SalairesType::class, $salaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($salaire);
            $em->flush();

            return $this->redirectToRoute('salaires_index');
        }

        return $this->render('salaires/new.html.twig', [
            'salaire' => $salaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="salaires_show", methods="GET")
     */
    public function show(Salaires $salaire): Response
    {
        return $this->render('salaires/show.html.twig', ['salaire' => $salaire]);
    }

    /**
     * @Route("/{id}/edit", name="salaires_edit", methods="GET|POST")
     */
    public function edit(Request $request, Salaires $salaire): Response
    {
        $form = $this->createForm(SalairesType::class, $salaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('salaires_index', ['id' => $salaire->getId()]);
        }

        return $this->render('salaires/edit.html.twig', [
            'salaire' => $salaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="salaires_delete", methods="DELETE")
     */
    public function delete(Request $request, Salaires $salaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$salaire->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($salaire);
            $em->flush();
        }

        return $this->redirectToRoute('salaires_index');
    }
}
