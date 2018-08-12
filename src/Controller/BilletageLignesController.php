<?php

namespace App\Controller;

use App\Entity\BilletageLignes;
use App\Entity\Billetages;
use App\Entity\Devises;
use App\Form\BilletageLignesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class BilletageLignesController extends Controller
{
    /**
     * @Route("/billetage/lignes", name="billetage_lignes_index", methods="GET")
     */
    public function index(): Response
    {
        $billetageLignes = $this->getDoctrine()
            ->getRepository(BilletageLignes::class)
            ->findAll();

        return $this->render('billetage_lignes/index.html.twig', ['billetage_lignes' => $billetageLignes]);
    }

    /**
     * @Route("/billetage/lignes/new", name="billetage_lignes_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $billetageLigne = new BilletageLignes();
        $form = $this->createForm(BilletageLignesType::class, $billetageLigne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($billetageLigne);
            $em->flush();

            return $this->redirectToRoute('billetage_lignes_index');
        }

        return $this->render('billetage_lignes/new.html.twig', [
            'billetage_ligne' => $billetageLigne,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/billetage/lignes/{id}", name="billetage_lignes_show", methods="GET")
     */
    public function show(BilletageLignes $billetageLigne): Response
    {
        return $this->render('billetage_lignes/show.html.twig', ['billetage_ligne' => $billetageLigne]);
    }

    /**
     * @Route("/billetage/lignes/{id}/edit", name="billetage_lignes_edit", methods="GET|POST")
     */
    public function edit(Request $request, BilletageLignes $billetageLigne): Response
    {
        $form = $this->createForm(BilletageLignesType::class, $billetageLigne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('billetage_lignes_edit', ['id' => $billetageLigne->getId()]);
        }

        return $this->render('billetage_lignes/edit.html.twig', [
            'billetage_ligne' => $billetageLigne,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/billetage/lignes/{id}", name="billetage_lignes_delete", methods="DELETE")
     */
    public function delete(Request $request, BilletageLignes $billetageLigne): Response
    {
        if ($this->isCsrfTokenValid('delete'.$billetageLigne->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($billetageLigne);
            $em->flush();
        }

        return $this->redirectToRoute('billetage_lignes_index');
    }

    /**
     * @Route("Wrsso/billetages/ajout1", name="billetage_ligne_ajout1")
     */
    public function ajout1Action(Request $request)
    {

          $em = $this->getDoctrine()->getManager();
          $billets = $em->getRepository('App:Billets')->findAll();
          //////////// creation du formulaire personnalise///////////////////////////////
          $formBilletage = $this->get('form.factory')->createNamedBuilder('formBilletage')->getForm();
          $i=1;
        foreach ($billets as $billet) {
              $formBilletage->add('valeur'.$i,TextType::class,array('disabled'=>true))
                  ->add('nbBillet'.$i,TextType::class)
                  ->add('valeurLigne'.$i,TextType::class);
              $i++;
          }
          $formBilletage->add('valeurTotal',IntegerType::class);
              //->add('Ajouter',SubmitType::class);

          //////////////////////// mise en place de valeurs par defaut du formulaire /////////////////
          $billets = $em->getRepository('App:Billets')->findAll();
          $i=1;
          foreach ($billets as $billet) {
              $formBilletage['valeur'.$i]->setData($billet);
              $formBilletage['nbBillet'.$i]->setData(0);
              $formBilletage['valeurLigne'.$i]->setData(0);
              $i++;
          }
          $formBilletage['valeurTotal']->setData(0);


          // only handles data on POST
          $formBilletage->handleRequest($request);
          if ($formBilletage->isSubmitted() && $formBilletage->isValid()) {
              $billetage=new Billetages();
              $billetage->setValeurTotal($formBilletage->get('valeurTotal')->getData());
              $billetage->setDateBillettage(new \DateTime('now'));
              $em = $this->getDoctrine()->getManager();
              $em->persist($billetage);
              $this->get('session')->set('billetage', $billetage);
              $em->flush();
              $i=1;
              foreach ($billets as $ligne){
                  $billetLigne = new BilletageLignes();
                  $billetLigne->setIdBilletage($billetage);
                  $billetLigne->setNbBillet($formBilletage->get('nbBillet'.$i)->getData());
                  $billetLigne->setValeurBillet($formBilletage->get('valeur'.$i)->getData());
                  $billetLigne->setValeurLigne($formBilletage->get('valeurLigne'.$i)->getData());
                  $i++;
                  $em->persist($billetLigne);

                  $em->flush();
              }
              $this->addFlash('success', 'Billet Créé!');
              return $this->redirectToRoute('billetage_lignes_index');
          }
          return $this->render('billetage_lignes/ajout.html.twig', [
              'billetForm' => $formBilletage->createView()
              ,'billet'=>$billets
          ]);
      }

    /**
     * @Route("/billetages/ajout/{devise}", name="billetage_ligne_ajout")
     */
    public function ajoutAction(Request $request, int $devise)
    {
        //$param=['classe'=>$classe,'champ'=>$champ,'devise'=>$devise];
        $em = $this->getDoctrine()->getManager();
        $billets = $em->getRepository('App:Billets')->findAll();
        $billetage0 = $this->getDoctrine()->getRepository(Billetages::class)->find(1);
        $billetages= array($billetage0,$billetage0,$billetage0);
        //////////// creation du formulaire personnalise///////////////////////////////
        $formBilletage = $this->get('form.factory')->createNamedBuilder('formBilletage')->getForm();
        $i=1;
        foreach ($billets as $billet) {
            $formBilletage->add('valeur'.$i,TextType::class,array('disabled'=>true))
                ->add('nbBillet'.$i,TextType::class)
                ->add('valeurLigne'.$i,TextType::class);
            $i++;
        }
        $formBilletage->add('valeurTotal',IntegerType::class);
        //->add('Ajouter',SubmitType::class);

        //////////////////////// mise en place de valeurs par defaut du formulaire /////////////////
        $billets = $em->getRepository('App:Billets')->findAll();
        $i=1;
        foreach ($billets as $billet) {
            $formBilletage['valeur'.$i]->setData($billet->getValeur());
            $formBilletage['nbBillet'.$i]->setData(0);
            $formBilletage['valeurLigne'.$i]->setData(0);
            $i++;
        }
        $formBilletage['valeurTotal']->setData(0);


        // only handles data on POST
        $formBilletage->handleRequest($request);
        if ($formBilletage->isSubmitted() && $formBilletage->isValid()) {
            $billetage=new Billetages();
            $billetage->setValeurTotal($formBilletage->get('valeurTotal')->getData());
            $billetage->setDateBillettage(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($billetage);
            //////////////////  mise en session du billetage /////////////////
            $billetages=$this->get('session')->get('billetage');
            $billetages[$devise] = $billetage;
            $this->get('session')->set('billetage', $billetages);
            ///////////////// fin ///////////////////////////////////////////
            //$this->get('session')->set('param', $param);
            $em->flush();
            $i=1;
            foreach ($billets as $ligne){
                $billetLigne = new BilletageLignes();
                $billetLigne->setIdBilletage($billetage);
                $billetLigne->setNbBillet($formBilletage->get('nbBillet'.$i)->getData());
                $billetLigne->setValeurBillet($formBilletage->get('valeur'.$i)->getData());
                $billetLigne->setValeurLigne($formBilletage->get('valeurLigne'.$i)->getData());
                $i++;
                $em->persist($billetLigne);

                $em->flush();
            }
            $this->addFlash('success', 'Billet Créé!');
            return $this->redirectToRoute('billetage_lignes_index');
        }
        return $this->render('billetage_lignes/ajout.html.twig', [
            'billetForm' => $formBilletage->createView()
            ,'billet'=>$billets
        ]);
    }

}
