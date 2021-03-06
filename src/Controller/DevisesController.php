<?php

namespace App\Controller;

use App\Entity\Devises;
use App\Entity\DevisesCollection;
use App\Form\DevisesType;
use App\Form\TauxDevisesType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * @Route("/devises")
 */
class DevisesController extends Controller
{
    private $devise;
    /**
     * @Route("/", name="devises_index", methods="GET")
     */
    public function index(): Response
    {
        $devises = $this->getDoctrine()
            ->getRepository(Devises::class)
            ->findAll();

        return $this->render('devises/index.html.twig', ['devises' => $devises]);
    }

    /**
     * @Route("/new", name="devises_new", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function new(Request $request): Response
    {
        $devise = new Devises();
        $form = $this->createForm(DevisesType::class, $devise);
        $form->handleRequest($request);
        $devises = $this->getDoctrine()
            ->getRepository(Devises::class)
            ->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($devise);
            $em->flush();

            return $this->redirectToRoute('devises_index');
        }

        return $this->render('devises/new.html.twig', [
            'devise' => $devise,
            'form' => $form->createView(),
            'devises' => $devises
        ]);
    }

    /**
     * @Route("/taux", name="taux_devises", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function setTaux(Request $request): Response
    {
        $em = $this->getDoctrine();
        $devises = $em->getRepository(Devises::class)->liste();
        $devise=new Devises();
        if ($devise = $request->request->get('_devise'))
            $devise = $em->getRepository(Devises::class)->find($devise);

        $form = $this->createForm(TauxDevisesType::class, $devise);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $devise = $em->getRepository(Devises::class)->findOneByCode($form['code']->getData());
            $devise->setTxReference($form['txReference']->getData());
            $devise->setFormuleAchat($form['formuleAchat']->getData());
            $devise->setFormuleVente($form['formuleVente']->getData());
            //dump($devise);die();
            $em->persist($devise);
            $em->flush();

            return $this->redirectToRoute('devises_index');
        }

        return $this->render('devises/tauxDevises.html.twig', [
            //'devise' => $devisesCollection,
            'form' => $form->createView(),
            'devises' => $devises
        ]);
    }

    /**
     * @Route("/{id}", name="devises_show", methods="GET")
     */
    public function show(Devises $devise): Response
    {
        $devises = $this->getDoctrine()
            ->getRepository(Devises::class)
            ->findAll();
        return $this->render('devises/show.html.twig', ['devise' => $devise,'devises' => $devises]);
    }

    /**
     * @Route("/{id}/edit", name="devises_edit", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function edit(Request $request, Devises $devise): Response
    {
        $form = $this->createForm(DevisesType::class, $devise);
        $form->handleRequest($request);
        $devises = $this->getDoctrine()
            ->getRepository(Devises::class)
            ->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('devises_edit', ['id' => $devise->getId()]);
        }

        return $this->render('devises/edit.html.twig', [
            'devise' => $devise,
            'form' => $form->createView(),
            'devises' => $devises
        ]);
    }


    /**
     * @Route("/{id}", name="devises_delete", methods="DELETE")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function delete(Request $request, Devises $devise): Response
    {
        if ($this->isCsrfTokenValid('delete'.$devise->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($devise);
            $em->flush();
        }

        return $this->redirectToRoute('devises_index');
    }
}
