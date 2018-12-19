<?php

namespace App\Controller;

use App\Entity\JourneeCaisses;
use App\Entity\TransfertInternationaux;
use App\Form\EmissionsType;
use App\Form\ReceptionsType;
use App\Form\TransfertInternationauxType;
use App\Form\TransfertType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Builder\ValidationBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//use Webmozart\Assert\Assert;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/transfert/internationaux")
 */
class TransfertInternationauxController extends Controller
{
    /**
     * @Route("/", name="transfert_internationaux_index", methods="GET")
     */
    public function index(): Response
    {
        $transfertInternationauxes = $this->getDoctrine()
            ->getRepository(TransfertInternationaux::class)
            ->findAll();

        return $this->render('transfert_internationaux/index.html.twig', ['transfert_internationauxes' => $transfertInternationauxes]);
    }

    /**
     * @Route("/new", name="transfert_internationaux_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $transfertInternationaux = new TransfertInternationaux();
        $form = $this->createForm(TransfertInternationauxType::class, $transfertInternationaux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($transfertInternationaux);
            $em->flush();

            return $this->redirectToRoute('transfert_internationaux_index');
        }

        return $this->render('transfert_internationaux/new.html.twig', [
            'transfert_internationaux' => $transfertInternationaux,
            'form' => $form->createView(),
        ]);
    }

    private function ajout(Request $request, $envoi = true): Response
    {
        $em =$this->getDoctrine();
        $journeeCaisse = ($request->request->get('_journeeCaisse'))?$em->getRepository(JourneeCaisses::class)->find($request->request->get('_journeeCaisse')):null;
            //dump($journeeCaisse->getTransfertInternationaux());die();
        ($envoi)?$journeeCaisse->setSensTransfert(TransfertInternationaux::ENVOI):
            $journeeCaisse->setSensTransfert(TransfertInternationaux::RECEPTION);
        $form = ($envoi)?$this->createForm(EmissionsType::class, $journeeCaisse):$this->createForm(ReceptionsType::class, $journeeCaisse);
        $form->handleRequest($request);
        //dump($form);die();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($journeeCaisse);
            $em->flush();
        }

        return $this->render('transfert_internationaux/ajout.html.twig', [
            'form' => $form->createView(),
            'operation'=>'',
            'envoi'=>$envoi,
            'journeeCaisse'=>$journeeCaisse
        ]);
    }

    /**
     * @Route("/envoi", name="transfert_internationaux_envoi", methods="GET|POST|UPDATE")
     */
    public function envoi(Request $request): Response
    {
        return $this->ajout($request, true);
    }
    /**
     * @Route("/reception", name="transfert_internationaux_reception", methods="GET|POST|UPDATE")
     */
    public function reception(Request $request): Response
    {
        return $this->ajout($request, false);
    }

    /**
     * @Route("/{id}", name="transfert_internationaux_show", methods="GET")
     */
    public function show(TransfertInternationaux $transfertInternationaux): Response
    {
        return $this->render('transfert_internationaux/show.html.twig', ['transfert_internationaux' => $transfertInternationaux]);
    }

    /**
     * @Route("/{id}/edit", name="transfert_internationaux_edit", methods="GET|POST")
     */
    public function edit(Request $request, TransfertInternationaux $transfertInternationaux): Response
    {
        $form = $this->createForm(TransfertInternationauxType::class, $transfertInternationaux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('transfert_internationaux_edit', ['id' => $transfertInternationaux->getId()]);
        }

        return $this->render('transfert_internationaux/edit.html.twig', [
            'transfert_internationaux' => $transfertInternationaux,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="transfert_internationaux_delete", methods="DELETE")
     */
    public function delete(Request $request, TransfertInternationaux $transfertInternationaux): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transfertInternationaux->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($transfertInternationaux);
            $em->flush();
        }

        return $this->redirectToRoute('transfert_internationaux_index');
    }
}
