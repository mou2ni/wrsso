<?php

namespace App\Controller;

use App\Entity\Caisses;
use App\Entity\JourneeCaisses;
use App\Entity\Utilisateurs;
use App\Form\JourneeCaissesType;
use App\Form\OuvertureType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/journee/caisses")
 */
class JourneeCaissesController extends Controller
{
    /**
     * @Route("/", name="journee_caisses_index", methods="GET")
     */
    public function index(): Response
    {
        $journeeCaisses = $this->getDoctrine()
            ->getRepository(JourneeCaisses::class)
            ->findAll();

        return $this->render('journee_caisses/index.html.twig', ['journee_caisses' => $journeeCaisses]);
    }

    /**
     * @Route("/new", name="journee_caisses_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $journeeCaiss = new JourneeCaisses();
        $form = $this->createForm(JourneeCaissesType::class, $journeeCaiss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($journeeCaiss);
            $em->flush();

            return $this->redirectToRoute('journee_caisses_index');
        }

        return $this->render('journee_caisses/new.html.twig', [
            'journee_caiss' => $journeeCaiss,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ouverture", name="journee_caisses_ouverture", methods="GET|POST")
     */
    public function ouverture(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $caisse=$em->getRepository('App:Caisses')->find(4);
        $journeeCaissePrec = $this->getJourneeCaissePrec($caisse);
        $journeeCaiss = new JourneeCaisses();
        $form = $this->createForm(OuvertureType::class, $journeeCaiss);
        //$form['idBilletOuv']->setData($this->get('session')->get('billetage'));
        $form['valeurBillet']->setData($this->get('session')->get('billetage')->getValeurTotal());
        $form['soldeElectOuv']->setData($this->get('session')->get('electronic')->getSoldeTotal());
        $form['idCaisse']->setData($caisse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $journeeCaiss->setDateOuv(new \DateTime('now'));
            $journeeCaiss->setDateFerm(new \DateTime('now'));

            $em->persist($journeeCaiss);
            $em->flush();

            return $this->redirectToRoute('journee_caisses_index');
        }

        return $this->render('journee_caisses/ouverture.html.twig', [
            //'journee_caiss' => $journeeCaiss,
            'form' => $form->createView(),
            'journeeCaissePrec'=>$journeeCaissePrec
        ]);
    }


    /**
     * @Route("/{id}", name="journee_caisses_show", methods="GET")
     */
    public function show(JourneeCaisses $journeeCaiss): Response
    {
        return $this->render('journee_caisses/show.html.twig', ['journee_caiss' => $journeeCaiss]);
    }

    /**
     * @Route("/{id}/edit", name="journee_caisses_edit", methods="GET|POST")
     */
    public function edit(Request $request, JourneeCaisses $journeeCaiss): Response
    {
        $form = $this->createForm(JourneeCaissesType::class, $journeeCaiss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('journee_caisses_edit', ['id' => $journeeCaiss->getId()]);
        }

        return $this->render('journee_caisses/edit.html.twig', [
            'journee_caiss' => $journeeCaiss,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="journee_caisses_delete", methods="DELETE")
     */
    public function delete(Request $request, JourneeCaisses $journeeCaiss): Response
    {
        if ($this->isCsrfTokenValid('delete'.$journeeCaiss->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($journeeCaiss);
            $em->flush();
        }

        return $this->redirectToRoute('journee_caisses_index');
    }

    public function getJourneeCaissePrec(Caisses $caisse)
    {
        $journeeCaissePrec = $this->getDoctrine()
            ->getRepository(JourneeCaisses::class)
            ->findOneBy(['idCaisse'=>$caisse, 'idJourneeSuivante'=>null, 'statut'=>'F']);
        return $journeeCaissePrec;
    }

}
