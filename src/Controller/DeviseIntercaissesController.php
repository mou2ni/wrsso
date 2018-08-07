<?php

namespace App\Controller;

use App\Entity\DeviseIntercaisses;
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
     * @Route("/demander", name="devise_intercaisses_demander", methods="GET|POST")
     */
    public function demander(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $journeeCaisse=$this->getDoctrine()->getRepository(JourneeCaisses::class)->findOneBy(['statut'=>'O']);

        //die($journeeCaisse);

        $deviseIntercaiss = new DeviseIntercaisses($journeeCaisse,$em);

        $deviseIntercaiss->setStatut($deviseIntercaiss::INIT);
        $form = $this->createForm(DeviseIntercaissesType::class, $deviseIntercaiss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->getClickedButton()->getName()=='close' ){
                // History -1 here

                //echo 'CLOSE ..............';
            }

            if ( $form->getClickedButton()->getName()=='reset'){
                // echo 'RESET ..............';
                $deviseIntercaiss = new DeviseIntercaisses($journeeCaisse,$em);
            }

            if ( $form->getClickedButton()->getName()=='save_and_close'){
                // echo 'RESET ..............';
                $em = $this->getDoctrine()->getManager();
                $em->persist($deviseIntercaiss);
                $em->flush();
            }


            return $this->redirectToRoute('devise_intercaisses_demander');
        }

        $devise_mvt_intercaisses=$this->getDoctrine()->getRepository(DeviseIntercaisses::class)->findMvtIntercaisses($journeeCaisse);
        $devise_tmp_mvt_intercaisses=$this->getDoctrine()->getRepository(DeviseIntercaisses::class)->findTmpMvtIntercaisses($journeeCaisse);

        return $this->render('devise_intercaisses/demander.html.twig', [
            'devise_intercaiss' => $deviseIntercaiss, 'devise_mvt_intercaisses'=>$devise_mvt_intercaisses
            , 'myJourneeCaisse'=>$journeeCaisse, 'devise_tmp_mvt_intercaisses'=>$devise_tmp_mvt_intercaisses,
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
     * @Route("/{id}/demande_annulation", name="devise_intercaisses_demande_annulation", methods="GET|POST")
     */
    public function demande_annulation(Request $request, DeviseIntercaisses $deviseIntercaiss): Response
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
     * @Route("/{id}/valider", name="devise_intercaisses_valider", methods="GET|POST")
     */
    public function valider(Request $request, DeviseIntercaisses $deviseIntercaiss): Response
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
     * @Route("/{id}/autoriser_annulation", name="devise_intercaisses_autoriser_annulation", methods="GET|POST")
     */
    public function autoriser_annulation(Request $request, DeviseIntercaisses $deviseIntercaiss): Response
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
