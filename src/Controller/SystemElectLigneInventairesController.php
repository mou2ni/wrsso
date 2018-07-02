<?php

namespace App\Controller;

use App\Entity\SystemElectLigneInventaires;
use App\Form\SystemElectLigneInventairesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/system/elect/ligne/inventaires")
 */
class SystemElectLigneInventairesController extends Controller
{
    /**
     * @Route("/", name="system_elect_ligne_inventaires_index", methods="GET")
     */
    public function index(): Response
    {
        $systemElectLigneInventaires = $this->getDoctrine()
            ->getRepository(SystemElectLigneInventaires::class)
            ->findAll();

        return $this->render('system_elect_ligne_inventaires/index.html.twig', ['system_elect_ligne_inventaires' => $systemElectLigneInventaires]);
    }

    /**
     * @Route("/new", name="system_elect_ligne_inventaires_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $systemElectLigneInventaire = new SystemElectLigneInventaires();
        $form = $this->createForm(SystemElectLigneInventairesType::class, $systemElectLigneInventaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($systemElectLigneInventaire);
            $em->flush();

            return $this->redirectToRoute('system_elect_ligne_inventaires_index');
        }

        return $this->render('system_elect_ligne_inventaires/new.html.twig', [
            'system_elect_ligne_inventaire' => $systemElectLigneInventaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="system_elect_ligne_inventaires_show", methods="GET")
     */
    public function show(SystemElectLigneInventaires $systemElectLigneInventaire): Response
    {
        //$systemElectLigneInventaires = $this->getDoctrine()
          //  ->getRepository(SystemElectLigneInventaires::class)
            //->findBy(['idSystemElectInventaire' => $systemElectInventaire->getId()]);
        return $this->render('system_elect_ligne_inventaires/show.html.twig', [
            'system_elect_ligne_inventaire' => $systemElectLigneInventaire
            //'system_elect_inventaire' => $systemElectLigneInventaires
        ]);
    }

    /**
     * @Route("/{id}/edit", name="system_elect_ligne_inventaires_edit", methods="GET|POST")
     */
    public function edit(Request $request, SystemElectLigneInventaires $systemElectLigneInventaire): Response
    {
        $form = $this->createForm(SystemElectLigneInventairesType::class, $systemElectLigneInventaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('system_elect_ligne_inventaires_edit', ['id' => $systemElectLigneInventaire->getId()]);
        }

        return $this->render('system_elect_ligne_inventaires/edit.html.twig', [
            'system_elect_ligne_inventaire' => $systemElectLigneInventaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="system_elect_ligne_inventaires_delete", methods="DELETE")
     */
    public function delete(Request $request, SystemElectLigneInventaires $systemElectLigneInventaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$systemElectLigneInventaire->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($systemElectLigneInventaire);
            $em->flush();
        }

        return $this->redirectToRoute('system_elect_ligne_inventaires_index');
    }
}
