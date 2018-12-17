<?php

namespace App\Controller;

use App\Entity\DeviseIntercaisses;
use App\Entity\DeviseMouvements;
use App\Entity\DeviseTmpMouvements;
use App\Entity\JourneeCaisses;
use App\Form\DeviseIntercaissesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/devise/intercaisses")
 */
class DeviseIntercaissesController extends Controller
{
    /*
     * @Route("/", name="devise_intercaisses_index", methods="GET")

    public function index(): Response
    {
        $deviseIntercaisses = $this->getDoctrine()
            ->getRepository(DeviseIntercaisses::class)
            ->findAll();

        return $this->render('devise_intercaisses/index.html.twig', ['devise_intercaiss' => $deviseIntercaisses]);
    }
*/
    /**
     * @Route("/", name="devise_intercaisses_gestion", methods="GET|POST")
     */
    public function demander(Request $request, JourneeCaisses $journeeCaisse ): Response
    {
        $em = $this->getDoctrine()->getManager();

        //dump($request);die();

        //$journeeCaisse = $em->getRepository(JourneeCaisses::class)->findOneBy(['statut' => 'O']);

        $deviseIntercaiss = new DeviseIntercaisses($journeeCaisse, $em);

        $deviseIntercaiss->setStatut($deviseIntercaiss::INIT);
        $form = $this->createForm(DeviseIntercaissesType::class, $deviseIntercaiss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $save_and_new=$form->getClickedButton()->getName()== 'save_and_new';
            //$save_and_print= $form->getClickedButton()->getName()== 'save_and_print';
            $save_and_close=$form->getClickedButton()->getName()== 'save_and_close';


            if ($save_and_new or $save_and_close) {
                $em->persist($deviseIntercaiss);
                $em->flush();
                if ($save_and_close) return $this->redirectToRoute('journee_caisses_gerer',['id'=>$journeeCaisse->getId()]);
                if ($save_and_new) return $this->redirectToRoute('devise_intercaisses_gestion', ['id'=>$journeeCaisse->getId(),]);

            }
        }
        $devise_mvt_intercaisses=$this->getDoctrine()->getRepository(DeviseIntercaisses::class)->findMvtIntercaisses($journeeCaisse);
        $devise_tmp_mvt_intercaisses=$this->getDoctrine()->getRepository(DeviseIntercaisses::class)->findTmpMvtIntercaisses($journeeCaisse);

        //dump($deviseIntercaiss); die();

        return $this->render('devise_intercaisses/gestion.html.twig', [
            'devise_intercaiss' => $deviseIntercaiss, 'devise_mvt_intercaisses'=>$devise_mvt_intercaisses
            , 'journeeCaisse'=>$journeeCaisse, 'devise_tmp_mvt_intercaisses'=>$devise_tmp_mvt_intercaisses,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/autoriser/{id}/{jc}", name="devise_intercaisses_autorisation", methods="UPDATE")
     */
    public function autoriser(Request $request, DeviseIntercaisses $deviseIntercaiss, $jc ): Response
    {
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
                    foreach ($deviseIntercaiss->getDeviseTmpMouvements() as $deviseTmpMouvement)
                    {
                        $deviseMouvement=new DeviseMouvements();
                        $deviseMouvement->setDevise($deviseTmpMouvement->getDevise())
                            ->setNombre($deviseTmpMouvement->getNombre())
                            ->setTaux($deviseTmpMouvement->getTaux())
                            ->setDeviseJourneeByJourneeCaisse($deviseIntercaiss->getJourneeCaisseDestination(),$em)
                        ;
                        $deviseIntercaiss->addDeviseMouvement($deviseMouvement);
                    }

                    $deviseIntercaiss->setStatut($deviseIntercaiss::VALIDE);
                }
                $em->flush();
            }
        }
        return $this->redirectToRoute('devise_intercaisses_gestion', ['id'=>$jc]);
    }

}
