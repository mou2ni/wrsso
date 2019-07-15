<?php

namespace App\Controller;

use App\Entity\DeviseIntercaisses;
use App\Entity\DeviseMouvements;
use App\Entity\DeviseTmpMouvements;
use App\Entity\JourneeCaisses;
use App\Form\DeviseIntercaissesType;
use App\Utils\SessionUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/devise/intercaisses")
 */
class DeviseIntercaissesController extends Controller
{
    private $journeeCaisse;
    private $utilisateur;

    public function __construct(SessionUtilisateur $sessionUtilisateur)
    {
        $this->utilisateur=$sessionUtilisateur->getUtilisateur();
        $this->journeeCaisse=$sessionUtilisateur->getJourneeCaisse();
        if(!$this->journeeCaisse){
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/", name="devise_intercaisses_gestion", methods="GET|POST")
     */
    public function demander(Request $request): Response
    {
        if($this->journeeCaisse->getStatut()!=JourneeCaisses::ENCOURS or
            $this->utilisateur->getId()!=$this->journeeCaisse->getUtilisateur()->getId()){
            $this->addFlash('error','Aucune journée ouverte. Merci d\'ouvrir une journée avant de continuer');
            return $this->redirectToRoute('journee_caisses_gerer');
        }
        
        $em = $this->getDoctrine()->getManager();

        $deviseIntercaiss = new DeviseIntercaisses($this->journeeCaisse, $em);

        $form = $this->createForm(DeviseIntercaissesType::class, $deviseIntercaiss,['dateComptable'=>$this->journeeCaisse->getDateComptable(),'myJournee'=>$this->journeeCaisse]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $save_and_new=$form->getClickedButton()->getName()== 'save_and_new';
            //$save_and_print= $form->getClickedButton()->getName()== 'save_and_print';
            $save_and_close=$form->getClickedButton()->getName()== 'save_and_close';


            if ($save_and_new or $save_and_close) {
                //dump($request->request->get('devise_intercaisses')['journeeCaissePartenaire']);die();
                //dump($request->request->get('devise_intercaisses'));die();
                if($deviseIntercaiss->getSens()==DeviseIntercaisses::ENTREE) {
                    $deviseIntercaiss->setJourneeCaisseSource($deviseIntercaiss->getJourneeCaissePartenaire());
                    $deviseIntercaiss->setJourneeCaisseDestination($this->journeeCaisse);

                    $deviseIntercaiss->addDeviseMouvementsFromTmp();
                    $deviseIntercaiss->setStatut($deviseIntercaiss::VALIDATION_AUTO);
                }else{
                    $deviseIntercaiss->setJourneeCaisseSource($this->journeeCaisse);
                    $deviseIntercaiss->setJourneeCaisseDestination($deviseIntercaiss->getJourneeCaissePartenaire());
                    $deviseIntercaiss->setStatut($deviseIntercaiss::INIT);

                }
                $em->persist($deviseIntercaiss);
                $em->flush();
                if ($save_and_close) return $this->redirectToRoute('journee_caisses_gerer');
                if ($save_and_new) return $this->redirectToRoute('devise_intercaisses_gestion');

            }
        }
        $devise_mvt_intercaisses=$this->getDoctrine()->getRepository(DeviseIntercaisses::class)->findMvtIntercaisses($this->journeeCaisse);
        $devise_tmp_mvt_intercaisses=$this->getDoctrine()->getRepository(DeviseIntercaisses::class)->findTmpMvtIntercaisses($this->journeeCaisse);

        //dump($deviseIntercaiss); die();

        return $this->render('devise_intercaisses/gestion.html.twig', [
            'devise_intercaiss' => $deviseIntercaiss, 'devise_mvt_intercaisses'=>$devise_mvt_intercaisses
            , 'journeeCaisse'=>$this->journeeCaisse, 'devise_tmp_mvt_intercaisses'=>$devise_tmp_mvt_intercaisses,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/autoriser/{id}/{jc}", name="devise_intercaisses_autorisation", methods="UPDATE")
     */
    public function autoriser(Request $request, DeviseIntercaisses $deviseIntercaiss, $jc ): Response
    {
        if($this->journeeCaisse->getStatut()!=JourneeCaisses::ENCOURS or
            $this->utilisateur->getId()!=$this->journeeCaisse->getUtilisateur()->getId()){
            $this->addFlash('error','Aucune journée ouverte. Merci d\'ouvrir une journée avant de continuer');
            return $this->redirectToRoute('journee_caisses_gerer');
        }
        //$deviseIntercaiss->setJourneeCaisse($this->journeeCaisse);
        if ($deviseIntercaiss->getStatut()<>DeviseIntercaisses::INIT){
            $this->addFlash('error', 'INTERCAISSE NON MODIFIABLE ! ! !');
            return $this->redirectToRoute('devise_intercaisses_gestion');
        }
        $em = $this->getDoctrine()->getManager();

        //dump($request);die();

        if ($request->getMethod()=='UPDATE'){ //Actions sur les intercaisses
            //$deviseIntercaiss=$em->getRepository(DeviseIntercaisses::class)->find($request->query->get('id'));
            $deviseIntercaiss->setEm($em);
            //dump($request);die();
            if ($this->isCsrfTokenValid('update'.$request->get('id'), $request->request->get('_token'))) {
                //dump($request->request->get('_token'));die();
                //bouton "annuler" cliqué
                if ( $request->request->has('annuler')){
                    $deviseIntercaiss->setStatut($deviseIntercaiss::ANNULE);
                }

                //bouton "valider" cliqué
                if ( $request->request->has('valider')){
                    $deviseIntercaiss->addDeviseMouvementsFromTmp();
                    $deviseIntercaiss->setStatut($deviseIntercaiss::VALIDE);
                }
                $em->flush();
            }
        }
        return $this->redirectToRoute('devise_intercaisses_gestion');
    }

    /**
    * @Route("/voir/{id}", name="devise_intercaisses_show", methods="GET")
    */
    public function show(Request $request, JourneeCaisses $journeeCaisse  ): Response
    {
        $devise_mvt_intercaisses=$this->getDoctrine()->getRepository(DeviseIntercaisses::class)->findMvtIntercaisses($journeeCaisse);
        $devise_tmp_mvt_intercaisses=$this->getDoctrine()->getRepository(DeviseIntercaisses::class)->findTmpMvtIntercaisses($journeeCaisse);

        //dump($deviseIntercaiss); die();

        return $this->render('devise_intercaisses/gestion.html.twig', [
            'devise_mvt_intercaisses'=>$devise_mvt_intercaisses
            , 'journeeCaisse'=>$journeeCaisse, 'devise_tmp_mvt_intercaisses'=>$devise_tmp_mvt_intercaisses,
        ]);
    }
}
