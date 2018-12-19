<?php

namespace App\Controller;

use App\Entity\BilletageLignes;
use App\Entity\Billetages;
use App\Entity\Billets;
use App\Entity\DeviseJournees;
use App\Entity\JourneeCaisses;
use App\Form\BilletagesType;
use App\Utils\SessionUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/billetages")
 */
class BilletagesController extends Controller
{
    private $journeeCaisse;

    public function __construct(SessionUtilisateur $sessionUtilisateur)
    {
        $this->journeeCaisse=$sessionUtilisateur->getJourneeCaisse();
        if(!$this->journeeCaisse){
            return $this->redirectToRoute('app_login');
        }
    }
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
     * @Route("/{id}/{devise}/{operation}", name="billetages_ajout", methods="GET|POST|UPDATE")
     */
    public function ajouter(Request $request, int $id, int $devise, $operation): Response
    {
       $em=$this->getDoctrine()->getManager();
        $billetage=$em->getRepository(Billetages::class)->find($id);
        $billets=$this->getDoctrine()->getRepository(Billets::class)->findActive($devise);

        if ($billetage->getBilletageLignes()->isEmpty()){
            foreach ($billets as $billet) {
                $billetageLigne=new BilletageLignes();
                $billetageLigne->setValeurBillet($billet->getValeur())->setNbBillet(0)->setBillet($billet);
                $billetage->addBilletageLigne($billetageLigne);
            }
        }

        $form = $this->createForm(BilletagesType::class, $billetage);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            switch ($operation){
                case 'liquiditeOuv' : $this->journeeCaisse->setMLiquiditeOuv($billetage->getValeurTotal());
                    $em->persist($this->journeeCaisse);
                    break;
                case 'liquiditeFerm' : $this->journeeCaisse->setMLiquiditeFerm($billetage->getValeurTotal());
                //dump($billetage); die();
                    $em->persist($this->journeeCaisse);
                    break;
                case 'deviseOuv' : $djOuv = $em->getRepository(DeviseJournees::class)->findOneBy(['billetOuv'=>$billetage]);
                    $djOuv->setQteOuv($billetage->getValeurTotal());
                    $em->persist($djOuv);
                    break;
                case 'deviseFerm' : $djFerm = $em->getRepository(DeviseJournees::class)->findOneBy(['billetFerm'=>$billetage]);
                    $djFerm->setQteFerm($billetage->getValeurTotal());
                    $em->persist($djFerm);
                    break;
            }
            $em->persist($billetage);
            $em->flush();
            $this->addFlash('success', 'Billetage enregistré!');
            return $this->redirectToRoute('journee_caisses_gerer');

        }

        return $this->render('billetages/ajout.html.twig', [
            'devise' => $devise,
            'billets' => $billets,
            'billetage' => $billetage,
            'form' => $form->createView(),
            'journeeCaisse'=>$this->journeeCaisse,
            'operation'=>''
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
