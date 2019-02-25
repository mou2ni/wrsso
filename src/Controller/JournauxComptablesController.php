<?php

namespace App\Controller;

use App\Entity\JournauxComptables;
use App\Entity\TransactionComptes;
use App\Form\JournauxComptablesType;
use App\Repository\JournauxComptablesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/journauxComptables")
 */
class JournauxComptablesController extends Controller
{
    /**
     * @Route("/", name="journaux_comptables_index", methods="GET")
     */
    public function index(Request $request): Response
    {
        $limit=20;
        $_page=$request->query->get('_page');
        $offset = ($_page)?($_page-1)*$limit:0;
        $liste = $this->getDoctrine()
            ->getRepository(JournauxComptables::class)
            ->liste($offset,$limit);
        $pages = round(count($liste)/$limit);
        return $this->render('journaux_comptables/index.html.twig', ['journaux_comptables' => $liste, 'pages'=>$pages, 'path'=>'journaux_comptables_index']);
    }

    /**
     * @Route("/ajout", name="journaux_comptables_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $journauxComptable = new JournauxComptables();
        $form = $this->createForm(JournauxComptablesType::class, $journauxComptable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($journauxComptable);
            $em->flush();

            return $this->redirectToRoute('journaux_comptables_index');
        }

        return $this->render('journaux_comptables/new.html.twig', [
            'journaux_comptable' => $journauxComptable,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/ecritures", name="journaux_comptables_ecritures", methods="GET")
     */
    public function ecritures(Request $request, JournauxComptables $journauxComptable): Response
    {
        $limit=20;
        $_page=$request->query->get('_page');
        $offset = ($_page)?($_page-1)*$limit:0;
        $liste = $this->getDoctrine()->getRepository(TransactionComptes::class)
            ->findEcrituresJournauxComptables(new \DateTime('2019-01-01 00:00:00'), new \DateTime(),$journauxComptable,$offset,$limit);
        $pages = round(count($liste)/$limit);
        //dump($liste);
        //$ecritures=$this->getDoctrine()->getRepository(TransactionComptes::class)->findEcrituresJournauxComptables(new \DateTime('2019-01-01 00:00:00'), new \DateTime(),$journauxComptable,$offset, $limit);
        return $this->render('journaux_comptables/ecritures_journal.html.twig', ['journal' => $journauxComptable,
            'ecritures' => $liste, 'pages'=>$pages, 'path'=>'journaux_comptables_ecritures', 'id'=>$journauxComptable->getId()]);
    }

    /**
     * @Route("/{id}/details", name="journaux_comptables_show", methods="GET")
     */
    public function show(Request $request, JournauxComptables $journauxComptable): Response
    {
        /*$limit=20;
        $_page=$request->query->get('_page');
        $offset = ($_page)?($_page-1)*$limit:0;
        $liste = $this->getDoctrine()->getRepository(TransactionComptes::class)
            ->findEcrituresJournauxComptables(new \DateTime('2019-01-01 00:00:00'), new \DateTime(),$journauxComptable,$offset,$limit);
        $pages = round(count($liste)/$limit);
        *///$ecritures=$this->getDoctrine()->getRepository(TransactionComptes::class)->findEcrituresJournauxComptables(new \DateTime('2019-01-01 00:00:00'), new \DateTime(),$journauxComptable,$offset, $limit);
        return $this->render('journaux_comptables/show.html.twig', ['journaux_comptable' => $journauxComptable,]);
    }

    /**
     * @Route("/{id}/modifier", name="journaux_comptables_edit", methods="GET|POST")
     */
    public function edit(Request $request, JournauxComptables $journauxComptable): Response
    {
        $form = $this->createForm(JournauxComptablesType::class, $journauxComptable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Journal enregistré correctement.');

            return $this->redirectToRoute('journaux_comptables_index', ['id' => $journauxComptable->getId()]);
        }

        return $this->render('journaux_comptables/edit.html.twig', [
            'journaux_comptable' => $journauxComptable,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/supprimer", name="journaux_comptables_delete", methods="DELETE")
     */
    public function delete(Request $request, JournauxComptables $journauxComptable): Response
    {
        if ($this->isCsrfTokenValid('delete'.$journauxComptable->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($journauxComptable);
            $em->flush();
        }

        return $this->redirectToRoute('journaux_comptables_index');
    }
}
