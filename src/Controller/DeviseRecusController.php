<?php

namespace App\Controller;

use App\Entity\DeviseJournees;
use App\Entity\DeviseMouvements;
use App\Entity\DeviseRecus;
use App\Form\DeviseRecusType;
use App\Repository\DeviseRecusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/devise/recus")
 */
class DeviseRecusController extends Controller
{
    /**
     * @Route("/", name="devise_recus_index", methods="GET")
     */
    public function index(DeviseRecusRepository $deviseRecusRepository): Response
    {
        return $this->render('devise_recus/index.html.twig', ['devise_recuses' => $deviseRecusRepository->findAll()]);
    }

    /**
     * @Route("/new", name="devise_recus_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $deviseJournee=$this->getDoctrine()->getRepository(DeviseJournees::class)->findOneBy(['qteOuv'=>110]);
        //$request->getSession()->set('deviseJournee',$deviseJournee);

        $_SESSION['deviseJournee']=$deviseJournee;

        $deviseRecus = new DeviseRecus();
        $form = $this->createForm(DeviseRecusType::class, $deviseRecus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /*foreach ( $deviseRecus->getDeviseMouvements() as $deviseMouvement){
                $deviseMouvement->setDeviseJournee($deviseJournee);

            }*/
            $em->persist($deviseRecus);
            $em->flush();

            return $this->redirectToRoute('devise_recus_index');
        }

        return $this->render('devise_recus/new.html.twig', [
            'devise_recus' => $deviseRecus,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="devise_recus_show", methods="GET")
     */
    public function show(DeviseRecus $deviseRecus): Response
    {
        return $this->render('devise_recus/show.html.twig', ['devise_recus' => $deviseRecus]);
    }

    /**
     * @Route("/{id}/edit", name="devise_recus_edit", methods="GET|POST")
     */
    public function edit(Request $request, DeviseRecus $deviseRecus): Response
    {
        $form = $this->createForm(DeviseRecusType::class, $deviseRecus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('devise_recus_edit', ['id' => $deviseRecus->getId()]);
        }

        return $this->render('devise_recus/edit.html.twig', [
            'devise_recus' => $deviseRecus,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="devise_recus_delete", methods="DELETE")
     */
    public function delete(Request $request, DeviseRecus $deviseRecus): Response
    {
        if ($this->isCsrfTokenValid('delete'.$deviseRecus->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($deviseRecus);
            $em->flush();
        }

        return $this->redirectToRoute('devise_recus_index');
    }
}
