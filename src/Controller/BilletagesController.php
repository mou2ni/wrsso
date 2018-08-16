<?php

namespace App\Controller;

use App\Entity\BilletageLignes;
use App\Entity\Billetages;
use App\Entity\Billets;
use App\Entity\JourneeCaisses;
use App\Form\BilletagesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/billetages")
 */
class BilletagesController extends Controller
{
    /**
     * @Route("/", name="billetages_index", methods="GET")
     */
    public function index(): Response
    {
        $billetages = $this->getDoctrine()
            ->getRepository(Billetages::class)
            ->findAll();

        return $this->render('billetages/index.html.twig', ['billetages' => $billetages]);
    }

    /**
 * @Route("/new", name="billetages_new", methods="GET|POST")
 */
    public function new(Request $request): Response
    {
        $billetage = new Billetages();
        $form = $this->createForm(BilletagesType::class, $billetage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($billetage);
            $em->flush();

            return $this->redirectToRoute('billetages_index');
        }

        return $this->render('billetages/new.html.twig', [
            'billetage' => $billetage,
            'form' => $form->createView(),
        ]);
    }




    /**
     * @Route("/{id}", name="billetages_ajout", methods="GET|POST")
     */
    public function ajouter(Request $request, Billetages $billetage): Response
    {
        $em=$this->getDoctrine()->getManager();
        $em->persist($billetage);
        $billets=$this->getDoctrine()->getRepository(Billets::class)->findActive();
        $em = $this->getDoctrine()->getManager();
        if (!$billetage->getBilletageLignes())
        foreach ($billets as $billet) {
            $billetageLigne=new BilletageLignes();
            $billetageLigne->setValeurBillet($billet->getValeur())->setNbBillet(0)->setBillet($billet);
            $billetage->addBilletageLignes($billetageLigne);
        }

        //dump($billetage);die();
        $billetage->setEm($this->getDoctrine()->getManager());
        //$billetage->getBilletageLigneAffiches();
        $form = $this->createForm(BilletagesType::class, $billetage);
        $form->handleRequest($request);
        //$billetage->fillBilletageLignes();
        //dump($billetage);die();
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($billetage);
            $em->flush();

            //return $this->redirectToRoute('billetages_ajout');
        }

        return $this->render('billetages/ajout.html.twig', [
            'billets' => $billets,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="billetages_edit", methods="GET|POST")
     */
    public function edit(Request $request, Billetages $billetage): Response
    {
        $form = $this->createForm(BilletagesType::class, $billetage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('billetages_edit', ['id' => $billetage->getId()]);
        }

        return $this->render('billetages/edit.html.twig', [
            'billetage' => $billetage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="billetages_show", methods="GET")
     */
    public function show(Billetages $billetage): Response
    {
        $billetageLignes = $this->getDoctrine()
            ->getRepository(BilletageLignes::class)
            ->findBy(['idBilletage' => $billetage]);
        return $this->render('billetages/show.html.twig', [
            'billetage' => $billetage,
            'billetage_lignes' => $billetageLignes]);
    }
    /**
     * @Route("/{id}", name="billetages_delete", methods="DELETE")
     */
    public function delete(Request $request, Billetages $billetage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$billetage->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($billetage);
            $em->flush();
        }

        return $this->redirectToRoute('billetages_index');
    }
}
