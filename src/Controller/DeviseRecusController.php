<?php

namespace App\Controller;

use App\Entity\Devises;
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
 * @Route("/devise/negoce")
 */
class DeviseRecusController extends Controller
{
    const COPIE1=0, COPIE2=600;
    /*
     * @Route("/", name="devise_recus_index", methods="GET")

    public function index(DeviseRecusRepository $deviseRecusRepository): Response
    {
        return $this->render('devise_recus/index.html.twig', ['devise_recuses' => $deviseRecusRepository->findAll()]);
    }
*/
    /**
     * @Route("/{id}", name="devise_recus_achat_vente", methods="GET|POST|UPDATE")
     */
    public function achatVente(Request $request, JourneeCaisses $journeeCaisse): Response
    {
        $em = $this->getDoctrine()->getManager();

        //$journeeCaisse=$this->getDoctrine()->getRepository(JourneeCaisses::class)->findOneBy(['statut'=>'O']);

        //die($journeeCaisse);


        $deviseRecus = new DeviseRecus($journeeCaisse,$em);

        ////////////////////////////////////////////////TESTS A SUPPRIMER//////////////////////////////////////////////

       $usd=$this->getDoctrine()->getRepository(Devises::class)->findOneBy(['code'=>'USD']);
        $euro=$this->getDoctrine()->getRepository(Devises::class)->findOneBy(['code'=>'EURO']);
        $deviseRecus->setNom('OUEDRAOGO')->setPrenom('Hamado')->setAdresse('837, Avenue DIMDOLOBSON, DAPOYA')->setNumPiece('B3520333')
            ->setMotif('Voyage affaire chine');

        /* $deviseMvt=new DeviseMouvements();
        $deviseMvt->setDevise($usd)->setNombre(100)->setTaux(500);
        $deviseRecus->addDeviseMouvement($deviseMvt);
        $deviseMvt=new DeviseMouvements();
        $deviseMvt->setDevise($euro)->setNombre(200)->setTaux(650);
        $deviseRecus->addDeviseMouvement($deviseMvt);*/


        ////////////////////////////////////////////////FIN TEST A SUPPRIMER

        $form = $this->createForm(DeviseRecusType::class, $deviseRecus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $save_and_new=$form->getClickedButton()->getName()== 'save_and_new';
            $save_and_print= $form->getClickedButton()->getName()== 'save_and_print';
            $save_and_close=$form->getClickedButton()->getName()== 'save_and_close';

            if ( $save_and_new  or $save_and_print or $save_and_close){
                //echo '$save_and_new  $save_and_print..............';

                //dump($form->getClickedButton()->getName()); die();

                $em->persist($deviseRecus);
                $em->flush();

                if ($save_and_print) {

                    //return $this->redirectToRoute('devise_recus_imprimer', ['devise_recus' => $deviseRecus]);

                    return $this->render('devise_recus/recu_impression.html.twig', ['devise_recus' => $deviseRecus,'devise_mouvements'=>$deviseRecus->getDeviseMouvements(),'journeeCaisse'=>$journeeCaisse, 'copies'=>[$this::COPIE1,$this::COPIE2]]);
                }

                if ($save_and_new) {
                   // echo '$save_and_new ..............';

                    $deviseRecusNew = new DeviseRecus($journeeCaisse,$em);
                    $deviseRecusNew->setDateRecu(new \DateTime());
                    $deviseRecusNew->setMotif($deviseRecus->getMotif());
                    $deviseRecusNew->setAdresse($deviseRecus->getAdresse());
                    $deviseRecusNew->setNom($deviseRecus->getNom());
                    $deviseRecusNew->setPrenom($deviseRecus->getPrenom());
                    $deviseRecusNew->setPaysPiece($deviseRecus->getPaysPiece());
                    $deviseRecusNew->setDeviseMouvements(new DeviseMouvements());

                    $deviseRecus=$deviseRecusNew;
                }

                if ($save_and_close) {
                    //dump($form->getClickedButton()->getName()); die();
                    return $this->redirectToRoute('journee_caisses_gerer',['id'=>$journeeCaisse->getId()]);
                }
            }
            return $this->redirectToRoute('devise_recus_achat_vente',['id'=>$journeeCaisse->getId()]);
        }

        $my_devise_recus=$this->getDoctrine()->getRepository(DeviseRecus::class)->findMyDeviseRecus($journeeCaisse);

        return $this->render('devise_recus/achat_vente.html.twig', [
            'devise_recus' => $deviseRecus, 'my_devise_recus'=>$my_devise_recus,
            'form' => $form->createView(),'journeeCaisse'=>$journeeCaisse,
        ]);
    }

    /**
     * @Route("/imprimer/{id}", name="devise_recus_imprimer", methods="GET|POST")
     */
    public function imprimer(Request $request, DeviseRecus $deviseRecus): Response
    {
        /*$form = $this->createForm(DeviseRecusType::class, $deviseRecus);
        $form->handleRequest($request);
        //if ($form->isSubmitted() && $form->isValid()){
        if ($request->query->has('retour')){
            return $this->redirectToRoute('devise_recus_achat_vente');
        }*/
        return $this->render('devise_recus/recu_impression.html.twig', ['devise_recus' => $deviseRecus,'devise_mouvements'=>$deviseRecus->getDeviseMouvements(),'journeeCaisse'=>$deviseRecus->getJourneeCaisse(), 'copies'=>[$this::COPIE1,$this::COPIE2]]);
    }

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
