<?php

namespace App\Controller;

use App\Entity\JourneeCaisses;
use App\Entity\TransfertInternationaux;
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

    /**
     * @Route("/ajout/{id}", name="transfert_internationaux_ajout", methods="GET|POST|UPDATE")
     */
    public function ajout(Request $request, JourneeCaisses $journeeCaisses, ValidatorInterface $validator): Response
    {
        //$journeeCaisse = $this->getDoctrine()->getRepository("App:JourneeCaisses")-> findOneBy(['statut' => 'O']);
        //dump($journeeCaisse); die();
        //$operation=$request->request->get('_operation');

        $form = $this->createForm(TransfertType::class, $journeeCaisses);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //$transfert=$form->getData();
            /*foreach ($journeeCaisses->getTransfertInternationaux() as $transfertInternationaux)
            //dump($journeeCaisses->getTransfertInternationaux()->getE());die();
            //if ($transfertInternationaux->getE())
                $constraint = new Assert\GreaterThan(0);
            //$constraint->message = "Valeur négative.";
            $errors = $validator->validate(
                $transfertInternationaux,
                $constraint
            );
            dump($errors);die();
            //$validator->rule($transfertInternationaux->getMTransfert() > 0);
            //$validator->rules;
                dump($transfertInternationaux->getE());die();*/
            //dump($journeeCaisses); die();
            //$errors=null;
            /*foreach ($journeeCaisses->getTransfertInternationaux() as $transfertInternationaux){
                //$journeeCaisses->addTransfertInternationaux($transfertInternationaux);
                !$transfertInternationaux->getE()?:$errors[]=$transfertInternationaux->getE();
            }*/
            //dump($transfert);die();
            $em->persist($journeeCaisses);
            $em->flush();
            /*if ($errors)
                $this->addFlash('error', 'Certaines lignes contiennent des valeurs négatives!');
            else
            return $this->redirectToRoute('transfert_internationaux_ajout', ['id'=>$journeeCaisses->getId()]);*/
        }

        return $this->render('transfert_internationaux/ajout.html.twig', [
            //'transfert_internationaux' => $journeeCaisses,
            'form' => $form->createView(),
            'operation'=>'',
            'journeeCaisse'=>$journeeCaisses
        ]);
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
