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
        //dump($sessionUtilisateur);die();
        $this->journeeCaisse=$sessionUtilisateur->getJourneeCaisse();

        if(!$this->journeeCaisse){
            return $this->redirectToRoute('app_login');
            //dump($this->journeeCaisse);die();

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

        //$billetage=$em->getRepository(Billetages::class)->find($id);
        $billetage= new Billetages();
        $billets=$this->getDoctrine()->getRepository(Billets::class)->findActive($devise);
        $detailLiquidite = '';
        switch ($operation){
            case 'liquiditeOuv' : $detailLiquidite = $this->journeeCaisse->getDetailLiquiditeOuv();
                break;
            case 'liquiditeFerm' : $detailLiquidite = $this->journeeCaisse->getDetailLiquiditeFerm();
            //dump($detailLiquidite);die();
                break;
            case 'deviseOuv' : $djOuv = $em->getRepository(DeviseJournees::class)->findOneBy(['journeeCaisse'=>$this->journeeCaisse,'devise'=>$devise]);
            $detailLiquidite = $djOuv->getDetailLiquiditeOuv();
                break;
            case 'deviseFerm' : $djFerm = $em->getRepository(DeviseJournees::class)->findOneBy(['journeeCaisse'=>$this->journeeCaisse,'devise'=>$devise]);
                $detailLiquidite = $djFerm->getDetailLiquiditeFerm();
                break;
        }

        foreach (explode(';',$detailLiquidite) as $lg) {
            //dump(count(explode('x',$lg)));
            if (count(explode('x', $lg)) > 1) {
                $nombre = explode('x', $lg)['1'];
                $valeur = explode('x', $lg)['0'];
                //dump($nombre);dump($valeur);

                $billet = $this->getDoctrine()
                    ->getRepository(Billets::class)
                    ->findOneBy(['valeur' => $valeur]);
                $bl = new BilletageLignes();
                $bl->setNbBillet($nombre)->setBillet($billet)->setValeurBillet($billet->getValeur());
                $billetage->addBilletageLignes($bl);
                //dump($billetage->getBilletageLignes());die();
                //$bl->setBilletages($billetage);

                //$em->persist($bl);
            }
        }
        if ($billetage->getBilletageLignes()->isEmpty())
        {
            foreach ($billets as $billet) {
                $billetageLigne=new BilletageLignes();
                $billetageLigne->setValeurBillet($billet->getValeur())->setNbBillet(0)->setBillet($billet);
                $billetage->addBilletageLignes($billetageLigne);
            }
            $em->persist($billetage);
            //dump($this->journeeCaisse->getDetailLiquiditeOuv());die();
        }


        //////SUPPRESSION D'EVENTUELLES LIGNES SUPPLEMENTAIRES
        while($billetage->getBilletageLignes()->count()>count($billets)) {
            $occurence=0;
            foreach ($billetage->getBilletageLignes() as $bl1){
                foreach ($billetage->getBilletageLignes() as $bl2) {
                    if ($bl1->getBillet() == $bl2->getBillet() && $bl1 != $bl2) {
                        $billetage->removeBilletageLigne($bl2);
                        $occurence=$occurence+1;
                        break;
                    }
                }
                if ($occurence>0){
                    break;
                }
            }

        }

        //dump($billetage); die();

        $form = $this->createForm(BilletagesType::class, $billetage);
        $form->handleRequest($request);


        //$lig=new BilletageLignes();$lig->getNbBillet()
        if ($form->isSubmitted() && $form->isValid()) {
            $detailBilletage = '';
            foreach ($billetage->getBilletageLignes() as $bl){
                $detailBilletage=$detailBilletage.''.$bl->getValeurBillet().'x'.$bl->getNbBillet().';';
                $billetage->setBilletageLigne($detailBilletage);
            }
            $em->persist($billetage);
            //$em->flush();
            switch ($operation){
                case 'liquiditeOuv' : $this->journeeCaisse->setMLiquiditeOuv($billetage->getValeurTotal())->setDetailLiquiditeOuv($detailBilletage);
                    $em->persist($this->journeeCaisse);
                    break;
                case 'liquiditeFerm' : $this->journeeCaisse->setMLiquiditeFerm($billetage->getValeurTotal())->setDetailLiquiditeFerm($detailBilletage);
                    $em->persist($this->journeeCaisse);
                    break;
                case 'deviseOuv' : $djOuv = $em->getRepository(DeviseJournees::class)->findOneBy(['journeeCaisse'=>$this->journeeCaisse,'devise'=>$devise]);
                $djPrec = $em->getRepository(DeviseJournees::class)->getDeviseJourneePrec($djOuv);
                $djOuv->setQteOuv($billetage->getValeurTotal())->setDetailLiquiditeOuv($detailBilletage);
                $djOuv->setEcartOuv(($djPrec)?$billetage->getValeurTotal() - $djPrec->getQteFerm():$billetage->getValeurTotal());
                    //dump($em->getRepository(DeviseJournees::class)->getDeviseJourneePrec($djOuv)); die();
                    $em->persist($djOuv);
                    break;
                case 'deviseFerm' : $djFerm = $em->getRepository(DeviseJournees::class)->findOneBy(['journeeCaisse'=>$this->journeeCaisse,'devise'=>$devise]);
                    $djFerm->setQteFerm($billetage->getValeurTotal())->setDetailLiquiditeFerm($detailBilletage);
                                   // dump($billetage);die();
                    $em->persist($djFerm);
                    break;
            }
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
     * @Route("/show/{id}/{devise}/{operation}", name="billetages_show", methods="GET|UPDATE")
     */
    public function show(JourneeCaisses $journeeCaisse, int $devise, $operation): Response
    {
        $em=$this->getDoctrine()->getManager();
        $billetage= new Billetages();
        $billets=$this->getDoctrine()->getRepository(Billets::class)->findActive($devise);
        $detailLiquidite = '';
        switch ($operation){
            case 'liquiditeOuv' : $detailLiquidite = $journeeCaisse->getDetailLiquiditeOuv();
                break;
            case 'liquiditeFerm' : $detailLiquidite = $journeeCaisse->getDetailLiquiditeFerm();
                //dump($detailLiquidite);die();
                break;
            case 'deviseOuv' : $djOuv = $em->getRepository(DeviseJournees::class)->findOneBy(['journeeCaisse'=>$journeeCaisse,'devise'=>$devise]);
                $detailLiquidite = $djOuv->getDetailLiquiditeOuv();
                break;
            case 'deviseFerm' : $djFerm = $em->getRepository(DeviseJournees::class)->findOneBy(['journeeCaisse'=>$journeeCaisse,'devise'=>$devise]);
                $detailLiquidite = $djFerm->getDetailLiquiditeFerm();
                break;
        }
        //dump($billetage->getBilletageLignes());
        $em=$this->getDoctrine()->getManager();
        //dump(count(explode('x',$billetage)));
        foreach (explode(';',$detailLiquidite) as $lg)
        {
            //dump(count(explode('x',$lg)));
            if (count(explode('x',$lg))>1){
                $nombre = explode('x',$lg)['1'];
                $valeur = explode('x',$lg)['0'];
                //dump($nombre);dump($valeur);

            $billet = $this->getDoctrine()
                ->getRepository(Billets::class)
                ->findOneBy(['valeur' => $valeur]);
            $bl = new BilletageLignes();
            $bl->setNbBillet($nombre)->setBillet($billet)->setValeurBillet($billet->getValeur());
            $billetage->addBilletageLignes($bl);
            //dump($billetage->getBilletageLignes());
            //$bl->setBilletages($billetage);

            //$em->persist($bl);
            }
        }            //dump($billetage);die();

        //dump($billetage);die();
        $billetageLignes = $billetage->getBilletageLignes();
        return $this->render('billetages/show.html.twig', [
            'billetage' => $billetage
            ,'billetage_lignes' => $billetageLignes
        ]);
    }
    /**
     * @Route("/{id}/maintenir", name="billetages_maint", methods="GET|UPDATE")
     */
    public function maintenir(Billetages $billetage): Response
    {
        $em = $this->getDoctrine()->getManager();
        $billetage->maintenir();
        $em->persist($billetage);
        $em->flush();
        $billetageLignes = $billetage->getBilletageLignes();
        $this->addFlash('success', 'Les données sont bonnes!');
        return $this->render('billetages/show.html.twig', [
            'billetage' => $billetage
            ,'billetage_lignes' => $billetageLignes
        ]);
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
