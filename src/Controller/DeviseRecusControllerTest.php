<?php

namespace App\Controller;

//use App\Entity\DeviseJournees;
//use App\Entity\DeviseMouvements;
use App\Entity\DeviseMouvements;
use App\Entity\DeviseRecus;
use App\Entity\JourneeCaisses;
use App\Form\DeviseRecusType;
use App\Repository\DeviseRecusRepository;
//use Proxies\__CG__\App\Entity\JourneeCaisses;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/devise/recus/test")
 */
class DeviseRecusControllerTest extends Controller
{
    /**
     * @Route("/", name="devise_recus_index", methods="GET")
     */
    public function index(DeviseRecusRepository $deviseRecusRepository): Response
    {
        return $this->render('devise_recus/index.html.twig', ['devise_recuses' => $deviseRecusRepository->findAll()]);
    }

    /*
     * @Route("/achat_vente", name="devise_recus_achat_vente_test", methods="GET|POST")
     */
    /*
    public function achatVente(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $journeeCaisse=$this->getDoctrine()->getRepository(JourneeCaisses::class)->findOneBy(['statut'=>'T']);

        //die($journeeCaisse);


        $deviseRecus = new DeviseRecus($journeeCaisse,$em);

        $form = $this->createForm(DeviseRecusType::class, $deviseRecus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            if ($form->getClickedButton()->getName()=='close' ){
                // History -1 here

                //echo 'CLOSE ..............';
            }

            if ( $form->getClickedButton()->getName()=='reset'){
               // echo 'RESET ..............';
                $deviseRecus = new DeviseRecus($journeeCaisse,$em);
            }

            $save_and_new=$form->getClickedButton()->getName()== 'save_and_new';
            $save_and_print= $form->getClickedButton()->getName()== 'save_and_print';


            if ( $save_and_new  or $save_and_print){
                //echo '$save_and_new  $save_and_print..............';

                $em->persist($deviseRecus);
                $em->flush();

                if ($save_and_print) {

                    //print  HERE
                    //echo '$save_and_print ..............';

                    $deviseRecusNew = new DeviseRecus($journeeCaisse,$em);
                }

                if ($save_and_new) {
                   // echo '$save_and_new ..............';

                    $deviseRecusNew = new DeviseRecus($journeeCaisse,$em);
                    $deviseRecusNew->setDateRecu(new \DateTime());
                    $deviseRecusNew->setMotif($deviseRecus->getMotif());
                    $deviseRecusNew->setNom($deviseRecus->getNom());
                    $deviseRecusNew->setPrenom($deviseRecus->getPrenom());
                    $deviseRecusNew->setPaysPiece($deviseRecus->getPaysPiece());
                    $deviseRecusNew->setDeviseMouvements(new DeviseMouvements());

                    $deviseRecus=$deviseRecusNew;
                }

            }

            return $this->redirectToRoute('devise_recus_achat_vente');
        }

        return $this->render('devise_recus/achat_vente.html.twig', [
            'devise_recus' => $deviseRecus, 'journeeCaisse'=>$journeeCaisse,
            'form' => $form->createView(),
        ]);
    }*/

    /**
     * @Route("/{id}", name="devise_recus_show", methods="GET")
     */
    public function show(DeviseRecus $deviseRecus): Response
    {
        return $this->render('devise_recus/show.html.twig', ['devise_recus' => $deviseRecus]);
    }

    /**
     * @Route("/{id}/edit", name="devise_recus_edit", methods="GET|POST")
     */
    public function edit(Request $request, DeviseRecus $deviseRecus): Response
    {
        $form = $this->createForm(DeviseRecusType::class, $deviseRecus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('devise_recus_edit', ['id' => $deviseRecus->getId()]);
        }

        return $this->render('devise_recus/edit.html.twig', [
            'devise_recus' => $deviseRecus,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="devise_recus_delete", methods="DELETE")
     */
    public function delete(Request $request, DeviseRecus $deviseRecus): Response
    {
        if ($this->isCsrfTokenValid('delete'.$deviseRecus->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($deviseRecus);
            $em->flush();
        }

        return $this->redirectToRoute('devise_recus_index');
    }

}
