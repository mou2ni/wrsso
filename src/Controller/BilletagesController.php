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
    private $utilisateur;

    public function __construct(SessionUtilisateur $sessionUtilisateur)
    {
        //dump($sessionUtilisateur);die();
        $this->journeeCaisse=$sessionUtilisateur->getJourneeCaisse();
        $this->utilisateur=$sessionUtilisateur->getUtilisateur();

        if(!$this->journeeCaisse){
            return $this->redirectToRoute('app_login');
            //dump($this->journeeCaisse);die();

        }
    }

    /**
     * @Route("/{devise}", name="billetages_ajout", methods="GET|POST|UPDATE")
     */
    public function compter(Request $request, $devise): Response
    {
        $billetage= new Billetages();

        $detailText=$request->query->get('dt');

        $billets=$this->getDoctrine()->getRepository(Billets::class)->findActive($devise);

        //$billetage->setBilletageLignesFromBillets($billets);

        if ($detailText){
            $billetage->setBilletageLignesFromText($detailText,$billets);
            //foreach ($billets)

        }else{
            $billetage->setBilletageLignesFromBillets($billets);
        }
 
        $form = $this->createForm(BilletagesType::class, $billetage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->enregistrer($request, $billetage, $devise);
            $return=$request->query->get('return');
            if ($return)return $this->redirectToRoute($return);
            else return $this->redirectToRoute('journee_caisses_gerer');
        }

            return $this->render('billetages/ajout.html.twig', [
            'devise' => $devise,
            //'billets' => $billets,
            'billetage' => $billetage,
            'form' => $form->createView(),
            'journeeCaisse'=>$this->journeeCaisse,
        ]);

    }

    private function enregistrer(Request $request, Billetages $billetage, $id=null){
        if ($this->journeeCaisse->getStatut() != JourneeCaisses::CLOSE and
            $this->utilisateur->getId() == $this->journeeCaisse->getUtilisateur()->getId()){

            $operation = $request->query->get('operation');
            $billetage->calcValeurTotal();

            //dump($billetage->getStringDetail());

            switch ($operation) {
                case 'liquiditeOuv' :
                    $this->journeeCaisse->setMLiquiditeOuv($billetage->getValeurTotal());
                    $this->journeeCaisse->setDetailLiquiditeOuv($billetage->getStringDetail());
                    break;
                case 'liquiditeFerm' :
                    $this->journeeCaisse->setMLiquiditeFerm($billetage->getValeurTotal());
                    $this->journeeCaisse->setDetailLiquiditeFerm($billetage->getStringDetail());
                    break;
                case 'deviseOuv' :
                    $deviseJournee=$this->getDoctrine()->getRepository(DeviseJournees::class)->find($request->query->get('id'));
                    $deviseJournee->setQteOuv($billetage->getValeurTotal());
                    $deviseJournee->setDetailLiquiditeOuv($billetage->getStringDetail());
                    //dump($deviseJournee);die();
                    $this->getDoctrine()->getManager()->persist($deviseJournee);
                    break;
                case 'deviseFerm' :
                    $deviseJournee=$this->getDoctrine()->getRepository(DeviseJournees::class)->find($request->query->get('id'));
                    $deviseJournee->setQteFerm($billetage->getValeurTotal());
                    $deviseJournee->setDetailLiquiditeFerm($billetage->getStringDetail());
                    $this->getDoctrine()->getManager()->persist($deviseJournee);
                    break;
            }
            if ($operation){
                $this->getDoctrine()->getManager()->persist($this->journeeCaisse);
                $this->getDoctrine()->getManager()->flush();
            }else $this->addFlash('warning','Non enregistré. Opération on précisé.');
        }else{
            $this->addFlash('error','Enregistrement impossible : Aucune journée ouverte. Merci d\'ouvrir une journée avant de continuer');
        }
    }

    /**
     * @Route("/show/", name="billetages_show", methods="GET|UPDATE")
     */
    public function show(Request $request): Response
    {
        $detailBilletage=$request->query->get('dt');
        /*$detailBilletage='';
        switch ($operation) {
            case 'liquiditeOuv' :
                $journeeCaisse=$this->getDoctrine()->getRepository(JourneeCaisses::class)->find($id);
                $detailBilletage=$journeeCaisse->getDetailLiquiditeOuv();
                break;
            case 'liquiditeFerm' :
                $journeeCaisse=$this->getDoctrine()->getRepository(JourneeCaisses::class)->find($id);
                $detailBilletage=$journeeCaisse->getDetailLiquiditeFerm();
                break;
            case 'deviseOuv' :
                $deviseJournee=$this->getDoctrine()->getRepository(DeviseJournees::class)->find($id);
                $detailBilletage=$deviseJournee->getDetailLiquiditeOuv();
                break;
            case 'deviseFerm' :
                $deviseJournee=$this->getDoctrine()->getRepository(DeviseJournees::class)->find($id);
                $detailBilletage=$deviseJournee->getDetailLiquiditeFerm();
                break;
        }*/
        $billetage=new Billetages();
        $billetage->setBilletageLignesFromText($detailBilletage);
        //dump($billetage);die();
        $billetageLignes = $billetage->getBilletageLignes();
        return $this->render('billetages/show.html.twig', [
            'billetage' => $billetage
            ,'billetage_lignes' => $billetageLignes
        ]);
    }
}
