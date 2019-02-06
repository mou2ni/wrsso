<?php

namespace App\Controller;

use App\Entity\Collaborateurs;
use App\Form\CollaborateursType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/collaborateurs")
 */
class CollaborateursController extends Controller
{
    /**
     * @Route("/", name="collaborateurs_index", methods="GET")
     */
    public function index(Request $request): Response
    {
        $limit=20;
        $_page=$request->query->get('_page');
        $offset = ($_page)?($_page-1)*$limit:0;
        $liste = $this->getDoctrine()
            ->getRepository(Collaborateurs::class)
            ->liste($offset,$limit);
        $pages = round(count($liste)/$limit);

        return $this->render('collaborateurs/index.html.twig', ['collaborateurs' => $liste, 'pages'=>$pages, 'path'=>'collaborateurs_index']);
    }

    /**
     * @Route("/ajout", name="collaborateurs_new", methods="GET|POST")
     */
    public function ajouter(Request $request): Response
    {
        $collaborateur = new Collaborateurs();
        /*$dateSortie=new \DateTime();
        $dateSortie->setDate(2100,1,1);
        $collaborateur->setDateSortie($dateSortie);*/
        $form = $this->createForm(CollaborateursType::class, $collaborateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($collaborateur);
            $em->flush();

            return $this->redirectToRoute('collaborateurs_new');
        }

        $limit=5;
        $_page=$request->query->get('_page');
        $offset = ($_page)?($_page-1)*$limit:0;
        $liste = $this->getDoctrine()
            ->getRepository(Collaborateurs::class)
            ->liste($offset,$limit);
        $pages = round(count($liste)/$limit);


        return $this->render('collaborateurs/new.html.twig', [
            'collaborateur' => $collaborateur,
            'form' => $form->createView(),
            'collaborateurs'=>$liste,
            'pages'=>$pages,
            'path'=>'collaborateurs_new'
        ]);
    }

    /**
     * @Route("/{id}", name="collaborateurs_show", methods="GET")
     */
    public function show(Collaborateurs $collaborateur): Response
    {
        return $this->render('collaborateurs/show.html.twig', ['collaborateur' => $collaborateur]);
    }

    /**
     * @Route("/{id}/edit", name="collaborateurs_edit", methods="GET|POST")
     */
    public function edit(Request $request, Collaborateurs $collaborateur): Response
    {
        $form = $this->createForm(CollaborateursType::class, $collaborateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('collaborateurs_index', ['id' => $collaborateur->getId()]);
        }

        return $this->render('collaborateurs/edit.html.twig', [
            'collaborateur' => $collaborateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="collaborateurs_delete", methods="DELETE")
     */
    public function delete(Request $request, Collaborateurs $collaborateur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$collaborateur->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($collaborateur);
            $em->flush();
        }

        return $this->redirectToRoute('collaborateurs_index');
    }
}
