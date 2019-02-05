<?php

namespace App\Controller;

use App\Entity\Caisses;
use App\Entity\JourneeCaisses;
use App\Form\CaissesType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/caisses")
 */
class CaissesController extends Controller
{
    /**
     * @Route("/", name="caisses_index", methods="GET")
     */
    public function index(): Response
    {
        $caisses = $this->getDoctrine()
            ->getRepository(Caisses::class)
            ->liste();

        return $this->render('caisses/index.html.twig', ['caisses' => $caisses]);
    }

    /**
     * @Route("/choisir", name="choisir_caisse", methods="GET|POST")
     */
    public function choisirCaisse(Request $request): Response
    {
        $caisses = $this->getDoctrine()
            ->getRepository(Caisses::class)
            ->findBy(['status'=>Caisses::FERME]);
        $form = $this->get('form.factory')->createNamedBuilder('form')->getForm();
        $form->add('caisse',EntityType::class,['class'=>Caisses::class, 'choices'=>$caisses]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $caiss=$form['caisse']->getData();
            $this->get('session')->get('journeeCaisse')->setIdCaisse($caiss);

            return $this->redirectToRoute('caisses_index');
        }

        return $this->render('caisses/choisirCaisse.html.twig',
            ['form' => $form->createView(),
                'caisses' => $caisses]);
    }

    /**
     * @Route("/reallocation", name="caisses_relocate", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function reallouerJourneeCaisse(Request $request, Caisses $caisse): Response
    {
        if (!$this->isGranted('ROLE_COMPTABLE')) {
            throw new AccessDeniedException('Attribution de nouvelle journee interdite pour votre profil.');
        }
        
        $journeeCaisse=$caisse->getLastJournee();
        //si journée caisse déjà disponible pour la caisse
        if ($journeeCaisse!=null){
            $journeeCaisse->setStatut(JourneeCaisses::CLOSE);
            $this->getDoctrine()->getManager()->persist($journeeCaisse);
        }
        $journeeCaisse=new JourneeCaisses($this->getDoctrine()->getManager());
        $journeeCaisse->setCaisse($caisse);
        $journeeCaisse->setStatut(JourneeCaisses::ENCOURS);
        $this->getDoctrine()->getManager()->persist($journeeCaisse);
        //$this->getDoctrine()->getManager()->persist($caisse);
        $this->getDoctrine()->getManager()->flush();

        return $this->render('caisses/show.html.twig', ['caiss' => $caisse]);
    }
    /**
     * @Route("/ajout", name="caisses_new", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function new(Request $request): Response
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_COMPTABLE')) {
            throw new AccessDeniedException('Ajout de caisse interdit pour votre profil.');
        }
        $caiss = new Caisses();
        $form = $this->createForm(CaissesType::class, $caiss);
        $form->handleRequest($request);
        $caisses = $this->getDoctrine()
            ->getRepository(Caisses::class)
            ->liste();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($caiss);
            $em->flush();

            return $this->redirectToRoute('caisses_index');
        }

        return $this->render('caisses/new.html.twig', [
            'caiss' => $caiss,
            'caisses' => $caisses,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/detail", name="caisses_show", methods="GET")
     */
    public function show(Caisses $caiss): Response
    {
        $caisses = $this->getDoctrine()
            ->getRepository(Caisses::class)
            ->liste();
        return $this->render('caisses/show.html.twig', ['caiss' => $caiss,'caisses' => $caisses]);
    }

    /**
     * @Route("/{id}/modifier", name="caisses_edit", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function edit(Request $request, Caisses $caiss): Response
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_COMPTABLE')) {
            throw new AccessDeniedException('Modification de caisse interdit pour votre profil');
        }
        $form = $this->createForm(CaissesType::class, $caiss);
        $form->handleRequest($request);
        $caisses = $this->getDoctrine()
            ->getRepository(Caisses::class)
            ->liste();
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('caisses_edit', ['id' => $caiss->getId()]);
        }

        return $this->render('caisses/edit.html.twig', [
            'caisses' => $caisses,
            'caiss' => $caiss,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="caisses_delete", methods="DELETE")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function delete(Request $request, Caisses $caiss): Response
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_COMPTABLE')) {
            throw new AccessDeniedException('Suppression de caisse interdit pour votre profil.');
        }
        if ($this->isCsrfTokenValid('delete'.$caiss->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($caiss);
            $em->flush();
        }

        return $this->redirectToRoute('caisses_index');
    }
}
