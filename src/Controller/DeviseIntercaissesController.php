<?php

namespace App\Controller;

use App\Entity\DeviseIntercaisses;
use App\Entity\DeviseMouvements;
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
     * @Route("/", name="devise_intercaisses_gestion", methods="GET|POST|UPDATE")
     */
    public function demander(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        //dump($request);die();

        if ($request->getMethod()=='UPDATE'){ //Actions sur les intercaisses
            $deviseIntercaiss=$em->getRepository(DeviseIntercaisses::class)->find($request->query->get('id'));
            $deviseIntercaiss->setEm($em);
            if ($this->isCsrfTokenValid('update'.$request->query->get('id'), $request->request->get('_token'))) {
                //bouton "annuler" cliqué
                if ( $request->request->has('annuler')){
                    $deviseIntercaiss->setStatut($deviseIntercaiss::ANNULE);
                }
                //bouton "annuler" cliqué
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

            return $this->redirectToRoute('devise_intercaisses_gestion');
        }

        $journeeCaisse = $em->getRepository(JourneeCaisses::class)->findOneBy(['statut' => 'O']);

        $deviseIntercaiss = new DeviseIntercaisses($journeeCaisse, $em);

        $deviseIntercaiss->setStatut($deviseIntercaiss::INIT);
        $form = $this->createForm(DeviseIntercaissesType::class, $deviseIntercaiss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->getClickedButton()->getName() == 'close') {
                // Retour à la journée caisse
                return $this->redirectToRoute('journee_caisses_show', ['journee_caiss' => $journeeCaisse]);
            }

            if ($form->getClickedButton()->getName() == 'save_and_close') {
                $em->persist($deviseIntercaiss);
                $em->flush();
            }
        }
        $devise_mvt_intercaisses=$this->getDoctrine()->getRepository(DeviseIntercaisses::class)->findMvtIntercaisses($journeeCaisse);
        $devise_tmp_mvt_intercaisses=$this->getDoctrine()->getRepository(DeviseIntercaisses::class)->findTmpMvtIntercaisses($journeeCaisse);

        return $this->render('devise_intercaisses/gestion.html.twig', [
            'devise_intercaiss' => $deviseIntercaiss, 'devise_mvt_intercaisses'=>$devise_mvt_intercaisses
            , 'myJourneeCaisse'=>$journeeCaisse, 'devise_tmp_mvt_intercaisses'=>$devise_tmp_mvt_intercaisses,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="devise_intercaisses_show", methods="GET")
     */
    public function show(DeviseIntercaisses $deviseIntercaiss): Response
    {
        return $this->render('devise_intercaisses/show.html.twig', ['devise_intercaiss' => $deviseIntercaiss]);
    }

    /**
     * @Route("/{id}/edit", name="devise_intercaisses_edit", methods="GET|POST")
     */
    public function edit(Request $request, DeviseIntercaisses $deviseIntercaiss): Response
    {
        $form = $this->createForm(DeviseIntercaissesType::class, $deviseIntercaiss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('devise_intercaisses_edit', ['id' => $deviseIntercaiss->getId()]);
        }

        return $this->render('devise_intercaisses/edit.html.twig', [
            'devise_intercaiss' => $deviseIntercaiss,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="devise_intercaisses_delete", methods="DELETE")
     */
    public function delete(Request $request, DeviseIntercaisses $deviseIntercaiss): Response
    {
        if ($this->isCsrfTokenValid('delete'.$deviseIntercaiss->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($deviseIntercaiss);
            $em->flush();
        }

        return $this->redirectToRoute('devise_intercaisses_index');
    }
}
