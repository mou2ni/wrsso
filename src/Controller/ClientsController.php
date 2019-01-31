<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Form\ClientsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/clients")
 */
class ClientsController extends Controller
{
    /**
     * @Route("/", name="clients_index", methods="GET")
     */
    public function index(): Response
    {
        $clients = $this->getDoctrine()
            ->getRepository(Clients::class)
            ->liste();

        return $this->render('clients/index.html.twig', ['clients' => $clients]);
    }

    /**
     * @Route("/ajout", name="clients_new", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function ajouter(Request $request): Response
    {
        $client = new Clients();
        $form = $this->createForm(ClientsType::class, $client);
        $form->handleRequest($request);
        $clients = $this->getDoctrine()
            ->getRepository(Clients::class)
            ->liste();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();

            return $this->redirectToRoute('clients_index');
        }

        return $this->render('clients/ajout.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
            'clients' => $clients
        ]);
    }

    /**
     * @Route("/{id}", name="clients_show", methods="GET")
     */
    public function show(Clients $client): Response
    {$clients = $this->getDoctrine()
        ->getRepository(Clients::class)
        ->liste();
        return $this->render('clients/show.html.twig', ['client' => $client,
            'clients' => $clients]);
    }

    /**
     * @Route("/{id}/edit", name="clients_edit", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function edit(Request $request, Clients $client): Response
    {
        $form = $this->createForm(ClientsType::class, $client);
        $form->handleRequest($request);
        $clients = $this->getDoctrine()
            ->getRepository(Clients::class)
            ->liste();
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('clients_edit', ['id' => $client->getId()]);
        }

        return $this->render('clients/edit.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
            'clients' => $clients
        ]);
    }

    /**
     * @Route("/{id}", name="clients_delete", methods="DELETE")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function delete(Request $request, Clients $client): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($client);
            $em->flush();
        }

        return $this->redirectToRoute('clients_index');
    }
}
