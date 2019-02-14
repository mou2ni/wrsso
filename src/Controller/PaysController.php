<?php

namespace App\Controller;

use App\Entity\Pays;
use App\Form\PaysType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pays")
 */
class PaysController extends Controller
{
    /**
     * @Route("/", name="pays_index", methods="GET")
     */
    public function index(Request $request): Response
    {
        $limit=20;
        $_page=$request->query->get('_page');
        $offset = ($_page)?($_page-1)*$limit:0;
        $liste = $this->getDoctrine()
            ->getRepository(Pays::class)
            ->liste($offset,$limit);
        $pages = round(count($liste)/$limit);

        return $this->render('pays/index.html.twig', ['pays' => $liste, 'pages'=>$pages, 'path'=>'pays_index']);
    }

    /**
     * @Route("/new", name="pays_new", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function new(Request $request): Response
    {
        $pay = new Pays();
        $form = $this->createForm(PaysType::class, $pay);
        $form->handleRequest($request);
        $pays = $this->getDoctrine()
            ->getRepository(Pays::class)
            ->liste();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pay);
            $em->flush();

            return $this->redirectToRoute('pays_index');
        }

        return $this->render('pays/new.html.twig', [
            'pay' => $pay,
            'form' => $form->createView(),
            'pays' => $pays
        ]);
    }

    /**
     * @Route("/{id}", name="pays_show", methods="GET")
     */
    public function show(Pays $pay): Response
    {
        $pays = $this->getDoctrine()
            ->getRepository(Pays::class)
            ->liste();
        return $this->render('pays/show.html.twig', ['pay' => $pay, 'pays' => $pays]);
    }

    /**
     * @Route("/{id}/edit", name="pays_edit", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function edit(Request $request, Pays $pay): Response
    {
        $form = $this->createForm(PaysType::class, $pay);
        $form->handleRequest($request);
        $pays = $this->getDoctrine()
            ->getRepository(Pays::class)
            ->liste();
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('pays_edit', ['id' => $pay->getId()]);
        }

        return $this->render('pays/edit.html.twig', [
            'pay' => $pay,
            'form' => $form->createView(),
            'pays' => $pays
        ]);
    }

    /**
     * @Route("/{id}", name="pays_delete", methods="DELETE")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function delete(Request $request, Pays $pay): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pay->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($pay);
            $em->flush();
        }

        return $this->redirectToRoute('pays_index');
    }
}
