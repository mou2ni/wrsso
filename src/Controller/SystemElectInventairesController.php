<?php

namespace App\Controller;

use App\Entity\SystemElectInventaires;
use App\Entity\SystemElectLigneInventaires;
use App\Form\SystemElectInventairesType;
use App\Form\SystemElectsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SystemElectInventairesController extends Controller
{
    /**
     * @Route("/system/elect/inventaires", name="system_elect_inventaires_index", methods="GET")
     */
    public function index(): Response
    {
        $systemElectInventaires = $this->getDoctrine()
            ->getRepository(SystemElectInventaires::class)
            ->findAll();

        return $this->render('system_elect_inventaires/index.html.twig', ['system_elect_inventaires' => $systemElectInventaires]);
    }

    /**
     * @Route("/system/elect/inventaires/new", name="system_elect_inventaires_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $systemElectInventaire = new SystemElectInventaires();
        $form = $this->createForm(SystemElectInventairesType::class, $systemElectInventaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($systemElectInventaire);
            $em->flush();

            return $this->redirectToRoute('system_elect_inventaires_index');
        }

        return $this->render('system_elect_inventaires/new.html.twig', [
            'system_elect_inventaire' => $systemElectInventaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/system/elect/inventaires/{id}", name="system_elect_inventaires_show", methods="GET")
     */
    public function show(SystemElectInventaires $systemElectInventaire): Response
    {
        $systemElectLigneInventaires = $this->getDoctrine()
            ->getRepository(SystemElectLigneInventaires::class)
            ->findBy(['idSystemElectInventaire' => $systemElectInventaire->getId()]);
        return $this->render('system_elect_inventaires/show.html.twig',[
            'system_elect_inventaire' => $systemElectInventaire,
            'system_elect_Ligne_inventaires' => $systemElectLigneInventaires
            ]);
    }

    /**
     * @Route("/system/elect/inventaires/{id}/edit", name="system_elect_inventaires_edit", methods="GET|POST")
     */
    public function edit(Request $request, SystemElectInventaires $systemElectInventaire): Response
    {
        $form = $this->createForm(SystemElectInventairesType::class, $systemElectInventaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('system_elect_inventaires_edit', ['id' => $systemElectInventaire->getId()]);
        }

        return $this->render('system_elect_inventaires/edit.html.twig', [
            'system_elect_inventaire' => $systemElectInventaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/system/elect/inventaires/{id}", name="system_elect_inventaires_delete", methods="DELETE")
     */
    public function delete(Request $request, SystemElectInventaires $systemElectInventaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$systemElectInventaire->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($systemElectInventaire);
            $em->flush();
        }

        return $this->redirectToRoute('system_elect_inventaires_index');
    }

    /**
     * @Route("Wrsso/electronique/lignes/ajout", name="electronique_ligne_ajout")
     */
    public function ajoutAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $systemElects = $em->getRepository('App:SystemElects')->findAll();
        //////////// creation du formulaire personnalise///////////////////////////////
        $formElect = $this->get('form.factory')->createNamedBuilder('formElectronic')->getForm();
        $i=1;
        foreach ($systemElects as $elect) {
            $formElect->add('SystemElect'.$i, TextType::class, array('disabled'=>true))
                ->add('solde'.$i,IntegerType::class);
            $i++;
        }
        $formElect->add('soldeTotal',IntegerType::class);
        //->add('Ajouter',SubmitType::class);

        //////////////////////// mise en place de valeurs par defaut du formulaire /////////////////
        //$elects = $em->getRepository('App:SystemElects')->findAll();
        $i=1;
        foreach ($systemElects as $elect) {
            $formElect['SystemElect'.$i]->setData($elect);
            $formElect['solde'.$i]->setData(0);
            $i++;
        }
        $formElect['soldeTotal']->setData(10);


        // only handles data on POST
        $formElect->handleRequest($request);
        if ($formElect->isSubmitted() && $formElect->isValid()) {
            $electInventaire=new SystemElectInventaires();
            $electInventaire->setSoldeTotal($formElect->get('soldeTotal')->getData());
            $electInventaire->setDateInventaire(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($electInventaire);
            $this->get('session')->set('electronic', $electInventaire);
            $em->flush();
            $i=1;
            foreach ($systemElects as $elect){
                $electLigneInventaire = new SystemElectLigneInventaires();
                $electLigneInventaire->setIdSystemElectInventaire($electInventaire);
                //$electLigneInventaire->setNbBillet($formElect->get('nbBillet'.$i)->getData());
                $electLigneInventaire->setIdSystemElect($formElect->get('SystemElect'.$i)->getData());
                $electLigneInventaire->setSolde($formElect->get('solde'.$i)->getData());
                $i++;
                $em->persist($electLigneInventaire);

                $em->flush();
            }
            $this->addFlash('success', 'Billet Créé!');
            return $this->redirectToRoute('system_elect_ligne_inventaires_index');
        }
        return $this->render('system_elect_ligne_inventaires/ajout.html.twig', [
            'systemElectsForm' => $formElect->createView()
            ,'elects'=>$systemElects
        ]);
    }
}
