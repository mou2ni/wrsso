<?php

namespace App\Controller;

use App\Entity\BilletageLignes;
use App\Entity\Billetages;
use App\Entity\Billets;
use App\Entity\JourneeCaisses;
use App\Form\BilletagesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/billetages")
 */
class BilletagesController extends Controller
{
    /**
     * @Route("/", name="billetages_index", methods="GET")
     */
    public function index(): Response
    {
        $em=$this->getDoctrine()->getManager();
        $billets=$this->getDoctrine()->getRepository(Billets::class)->findAll();
        $billetage=new Billetages();
        foreach ($billets as $billet) {
            $billetageLigne=new BilletageLignes();
            $billetageLigne->setValeurBillet($billet->getValeur())->setNbBillet(0)->setBillet($billet);
            $billetage->addBilletageLigne($billetageLigne);
        }
        $form = $this->createForm(BilletagesType::class, $billetage);
        //$form->handleRequest($request);
        return $this->render('billetages/billetage.html.twig', [
            'billets' => $billets,
            'billetage' => $billetage,
            'form' => $form->createView(),
        ]);

        /*$billetages = $this->getDoctrine()
            ->getRepository(Billetages::class)
            ->findAll();

        return $this->render('billetages/index.html.twig', ['billetages' => $billetages]);*/
    }

    /**
 * @Route("/new", name="billetages_new", methods="GET|POST|UPDATE")
 */
    public function new(Request $request): Response
    {
        if ($request->getMethod()=='UPDATE')
        $billetage = new Billetages();
        $form = $this->createForm(BilletagesType::class, $billetage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($billetage);
            $em->flush();

            return $this->redirectToRoute('billetages_index');
        }

        return $this->render('billetages/new.html.twig', [
            'billetage' => $billetage,
            'form' => $form->createView(),
        ]);
    }




    /**
     * @Route("/{id}/{devise}", name="billetages_ajout", methods="GET|POST|UPDATE")
     */
    public function ajouter(Request $request, int $id, int $devise): Response
    {
        $em=$this->getDoctrine()->getManager();
        $billetage=$em->getRepository(Billetages::class)->find($id);
        $billets=$this->getDoctrine()->getRepository(Billets::class)->findActive($devise);
        $operation=$request->request->get('_operation');

        if ($billetage->getBilletageLignes()->isEmpty()){
            foreach ($billets as $billet) {
                $billetageLigne=new BilletageLignes();
                $billetageLigne->setValeurBillet($billet->getValeur())->setNbBillet(0)->setBillet($billet);
                $billetage->addBilletageLigne($billetageLigne);
            }
        }

        //if ($this->isCsrfTokenValid('billetage'.$id, $request->request->get('_token'))){
            $jc = $em->getRepository(JourneeCaisses::class)->find($request->request->get('_journeeCaisse'));
        //}

        $form = $this->createForm(BilletagesType::class, $billetage);
        $form->handleRequest($request);
        /*if ($operation=='OUVERT') {
            if ($devise == 0) {
                $jc->setBilletOuv($billetage);
                $jc->setMLiquiditeOuv($billetage->getValeurTotal());
            } else {
                foreach ($jc->getDeviseJournees() as $deviseJournee)
                    if ($deviseJournee->getDevise()->getId() == $devise)
                        $deviseJournee->setQteOuv($billetage->getValeurTotal());
            }
        }
        elseif ($operation=='FERMER') {

            if ($devise == 0) {
                $jc->setBilletFerm($billetage);
                $jc->setMLiquiditeFerm($billetage->getValeurTotal());
                //dump($jc);die();
            } else {
                foreach ($jc->getDeviseJournees() as $deviseJournee)
                    if ($deviseJournee->getDevise()->getId() == $devise)
                        $deviseJournee->setQteFerm($billetage->getValeurTotal());
            }
        }*/

        if ($form->isSubmitted() && $form->isValid()) {
            $jc = $em->getRepository(JourneeCaisses::class)->find($request->request->get('_journeeCaisse'));
            $em->persist($billetage);
            $jc->setMLiquiditeOuv($jc->getBilletOuv()->getValeurTotal());
            $jc->setMLiquiditeFerm($jc->getBilletFerm()->getValeurTotal());
            $em->persist($jc);
            //$billetage->getJourneeCaisse()->setMLiquiditeOuv($billetage->getValeurTotal());
            $em->flush();
            if ($operation=="FERMER")
                return $this->redirectToRoute('journee_caisses_gerer');
            return $this->redirectToRoute('journee_caisses_ouvrir');
        }

        return $this->render('billetages/ajout.html.twig', [
            'devise' => $devise,
            'billets' => $billets,
            'billetage' => $billetage,
            'form' => $form->createView(),
            'journeeCaisse'=>$jc,
            'operation'=>$operation
        ]);
    }

    /**
     * @Route("/{id}/edit", name="billetages_edit", methods="GET|POST")
     */
    public function edit(Request $request, Billetages $billetage): Response
    {
        $form = $this->createForm(BilletagesType::class, $billetage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('billetages_edit', ['id' => $billetage->getId()]);
        }

        return $this->render('billetages/edit.html.twig', [
            'billetage' => $billetage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="billetages_show", methods="GET")
     */
    public function show(Billetages $billetage): Response
    {
        $billetageLignes = $this->getDoctrine()
            ->getRepository(BilletageLignes::class)
            ->findBy(['idBilletage' => $billetage]);
        return $this->render('billetages/show.html.twig', [
            'billetage' => $billetage,
            'billetage_lignes' => $billetageLignes]);
    }
    /**
     * @Route("/{id}", name="billetages_delete", methods="DELETE")
     */
    public function delete(Request $request, Billetages $billetage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$billetage->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($billetage);
            $em->flush();
        }

        return $this->redirectToRoute('billetages_index');
    }
}
