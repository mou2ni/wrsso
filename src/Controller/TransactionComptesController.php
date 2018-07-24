<?php

namespace App\Controller;

use App\Entity\TransactionComptes;
use App\Form\DepotRetraitType;
use App\Form\TransactionComptesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/transaction/comptes")
 */
class TransactionComptesController extends Controller
{
    /**
     * @Route("/", name="transaction_comptes_index", methods="GET")
     */
    public function index(): Response
    {
        $transactionComptes = $this->getDoctrine()
            ->getRepository(TransactionComptes::class)
            ->findAll();

        return $this->render('transaction_comptes/index.html.twig', ['transaction_comptes' => $transactionComptes]);
    }

    /**
     * @Route("/ajout", name="transaction_comptes_ajout", methods="GET|POST")
     */
    public function ajout(Request $request): Response
    {
        $transactionCompte = new TransactionComptes();
        $form = $this->createForm(DepotRetraitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($transactionCompte);
            $em->flush();

            return $this->redirectToRoute('transaction_comptes_index');
        }

        return $this->render('transaction_comptes/new.html.twig', [
            'transaction_compte' => $transactionCompte,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="transaction_comptes_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $transactionCompte = new TransactionComptes();
        $form = $this->createForm(TransactionComptesType::class, $transactionCompte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($transactionCompte);
            $em->flush();

            return $this->redirectToRoute('transaction_comptes_index');
        }

        return $this->render('transaction_comptes/new.html.twig', [
            'transaction_compte' => $transactionCompte,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="transaction_comptes_show", methods="GET")
     */
    public function show(TransactionComptes $transactionCompte): Response
    {
        return $this->render('transaction_comptes/show.html.twig', ['transaction_compte' => $transactionCompte]);
    }

    /**
     * @Route("/{id}/edit", name="transaction_comptes_edit", methods="GET|POST")
     */
    public function edit(Request $request, TransactionComptes $transactionCompte): Response
    {
        $form = $this->createForm(TransactionComptesType::class, $transactionCompte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('transaction_comptes_edit', ['id' => $transactionCompte->getId()]);
        }

        return $this->render('transaction_comptes/edit.html.twig', [
            'transaction_compte' => $transactionCompte,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="transaction_comptes_delete", methods="DELETE")
     */
    public function delete(Request $request, TransactionComptes $transactionCompte): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transactionCompte->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($transactionCompte);
            $em->flush();
        }

        return $this->redirectToRoute('transaction_comptes_index');
    }
}
