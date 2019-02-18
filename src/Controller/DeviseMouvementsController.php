<?php

namespace App\Controller;

use App\Entity\DeviseAchatVentes;
use App\Entity\DeviseMouvements;
use App\Entity\DeviseRecus;
use App\Entity\JourneeCaisses;
use App\Form\DeviseMouvementsType;
use App\Form\DeviseAchatVentesType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/devise/mouvements")
 */
class DeviseMouvementsController extends Controller
{

    /**
     * @Route("/", name="devise_mouvements_index", methods="GET")
     */
    public function index(Request $request): Response
    {
        $date = new \DateTime();
        $dateDeb=new \DateTime('2019-01-01 00:00:00');
        $dateFin=new \DateTime('2019-01-01 00:00:00');
        $debut = $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('d'))->format('Y/m/d');
        $fin = $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('d'))->format('Y/m/d');
        $limit=10;
        $_page=$request->query->get('_page');
        $offset = ($_page)?($_page-1)*$limit:0;

        $deviseMouvements = $this->getDoctrine()
            ->getRepository(DeviseMouvements::class)
            ->findMouvement($dateDeb,$dateFin,$offset,$limit);
        $pages = round(count($deviseMouvements)/$limit);
        //dump($deviseMouvements);die();

        return $this->render('devise_mouvements/index.html.twig',
            [
                'devise_mouvements' => $deviseMouvements,
                'pages'=>$pages,
            ]);
    }
    /**
     * @Route("/etat", name="devise_mouvements_etat", methods="GET")
     */
    public function etat(Request $request): Response
    {
        $date = new \DateTime();
        $dateDeb=new \DateTime('2019-01-01 00:00:00');
        $dateFin=new \DateTime('2019-01-01 00:00:00');
        $debut = $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('1'));
        $fin = $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('t'));
        $limit=10;
        if (($request->query->get('_dateDeb')))
            $debut = new \DateTime($request->query->get('_dateDeb'));
        elseif ($request->query->get('dateDeb'))
            $debut = new \DateTime($request->query->get('dateDeb'));
        if ($request->query->get('_dateFin'))
            $fin = new \DateTime($request->query->get('_dateFin'));
        //elseif ($request->query->get('dateFin'))
            //dump($request->query->get('_dateFin'));die();
          //  $fin = new \DateTime($request->query->get('dateFin'));

        $_page=$request->query->get('_page');
        $offset = ($_page)?($_page-1)*$limit:0;
        $deviseMouvements = $this->getDoctrine()
            ->getRepository(DeviseMouvements::class)
            ->findMouvement($debut,$fin,$offset,$limit);
        $pages = round(count($deviseMouvements)/$limit);


        return $this->render('devise_mouvements/etat.html.twig',
            [
                'devise_mouvements' => $deviseMouvements,
                'pages'=>$pages,
                'dateDeb'=>$debut,
                'dateFin'=>$fin,
            ]);
    }

    /**
     * @Route("/new", name="devise_mouvements_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $deviseMouvement = new DeviseMouvements();
        $form = $this->createForm(DeviseMouvementsType::class, $deviseMouvement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($deviseMouvement);
            $em->flush();

            return $this->redirectToRoute('devise_mouvements_index');
        }

        return $this->render('devise_mouvements/new.html.twig', [
            'devise_mouvement' => $deviseMouvement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/achatvente", name="devise_mouvements_achatvente", methods="GET|POST")
     */
    public function achatVente(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        //$idJourneeCaisse=$request->getSession()->get('journeeCaisses');
        $idJourneeCaisse=$em->getRepository(JourneeCaisses::class)->findOneBy(['statut'=>'T']);

        $deviseAchatVente = new DeviseAchatVentes();
        $deviseAchatVente->setJournalAchatVente($em->getRepository(DeviseMouvements::class)->findBy(['idJourneeCaisse'=>$idJourneeCaisse]));

        $form = $this->createForm(DeviseAchatVentesType::class, $deviseAchatVente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $deviseMouvement= new DeviseMouvements();
            $deviseRecu= new DeviseRecus();
            


            $em = $this->getDoctrine()->getManager();
            $em->persist($deviseMouvement);
            $em->persist($deviseRecu);
            $em->flush();

            return $this->redirectToRoute('devise_mouvements_achatvente');
        }

        return $this->render('devise_mouvements/achat_vente.html.twig', [
            'devise_achatvente' => $deviseAchatVente,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="devise_mouvements_show", methods="GET")
     */
    public function show(DeviseMouvements $deviseMouvement): Response
    {
        return $this->render('devise_mouvements/show.html.twig', ['devise_mouvement' => $deviseMouvement]);
    }

    /**
     * @Route("/{id}/edit", name="devise_mouvements_edit", methods="GET|POST")
     */
    public function edit(Request $request, DeviseMouvements $deviseMouvement): Response
    {
        $form = $this->createForm(DeviseMouvementsType::class, $deviseMouvement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('devise_mouvements_edit', ['id' => $deviseMouvement->getId()]);
        }

        return $this->render('devise_mouvements/edit.html.twig', [
            'devise_mouvement' => $deviseMouvement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="devise_mouvements_delete", methods="DELETE")
     */
    public function delete(Request $request, DeviseMouvements $deviseMouvement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$deviseMouvement->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($deviseMouvement);
            $em->flush();
        }

        return $this->redirectToRoute('devise_mouvements_index');
    }

}
