<?php

namespace App\Controller;

use App\Entity\DeviseIntercaisses;
use App\Entity\DeviseMouvements;
use App\Entity\Devises;
use App\Entity\JourneeCaisses;
use App\Form\DeviseIntercaissesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/devise/intercaisses")
 */
class DeviseIntercaissesController extends Controller
{
    /**
     * @Route("/", name="devise_intercaisses_index", methods="GET")
     */
    public function index(): Response
    {
        $deviseIntercaisses = $this->getDoctrine()
            ->getRepository(DeviseIntercaisses::class)
            ->findAll();

        return $this->render('devise_intercaisses/index.html.twig', ['devise_intercaisses' => $deviseIntercaisses]);
    }

    /**
     * @Route("/new", name="devise_intercaisses_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $journeeCaisse=$this->getDoctrine()->getRepository(JourneeCaisses::class)->findOneBy(['statut'=>'T']);

        //die($journeeCaisse);

        $deviseIntercaiss = new DeviseIntercaisses($journeeCaisse,$em);

        //////////////////////////////////////// TEST A SUPPRIMER

       /* $usd=$this->getDoctrine()->getRepository(Devises::class)->findOneBy(['code'=>'USD']);
        $euro=$this->getDoctrine()->getRepository(Devises::class)->findOneBy(['code'=>'EURO']);
        $journeeCaissePartenaire=$this->getDoctrine()->getRepository(JourneeCaisses::class)->findOneBy(['statut'=>'O']);

        $deviseIntercaiss->setIdJourneeCaissePartenaire($journeeCaissePartenaire);

        $deviseMvt=new DeviseMouvements();
        $deviseMvt->setDevise($usd)->setNombre(10);
        $deviseIntercaiss->addDeviseMouvement($deviseMvt);
        $deviseMvt=new DeviseMouvements();
        $deviseMvt->setDevise($euro)->setNombre(20);
        $deviseIntercaiss->addDeviseMouvement($deviseMvt);*/


        ////////////////////////////// FIN

        $deviseIntercaiss->setStatut($deviseIntercaiss::VALIDATION_AUTO);
        $form = $this->createForm(DeviseIntercaissesType::class, $deviseIntercaiss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($deviseIntercaiss);
            $em->flush();

            return $this->redirectToRoute('devise_intercaisses_new');
        }

        return $this->render('devise_intercaisses/new.html.twig', [
            'devise_intercaiss' => $deviseIntercaiss,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="devise_intercaisses_show", methods="GET")
     */
    public function show(DeviseIntercaisses $deviseIntercaiss): Response
    {
        return $this->render('devise_intercaisses/show.html.twig', ['devise_intercaiss' => $deviseIntercaiss]);
    }

    /**
     * @Route("/{id}/edit", name="devise_intercaisses_edit", methods="GET|POST")
     */
    public function edit(Request $request, DeviseIntercaisses $deviseIntercaiss): Response
    {
        $form = $this->createForm(DeviseIntercaissesType::class, $deviseIntercaiss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('devise_intercaisses_edit', ['id' => $deviseIntercaiss->getId()]);
        }

        return $this->render('devise_intercaisses/edit.html.twig', [
            'devise_intercaiss' => $deviseIntercaiss,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="devise_intercaisses_delete", methods="DELETE")
     */
    public function delete(Request $request, DeviseIntercaisses $deviseIntercaiss): Response
    {
        if ($this->isCsrfTokenValid('delete'.$deviseIntercaiss->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($deviseIntercaiss);
            $em->flush();
        }

        return $this->redirectToRoute('devise_intercaisses_index');
    }
}
