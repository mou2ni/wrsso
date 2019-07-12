<?php

namespace App\Controller;

use App\Entity\Agences;
use App\Entity\CriteresDates;
use App\Entity\DeviseAchatVentes;
use App\Entity\DeviseMouvements;
use App\Entity\DeviseRecus;
use App\Entity\Devises;
use App\Entity\JourneeCaisses;
use App\Form\CriteresRecherchesJourneeCaissesType;
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
     * @Route("/deviseCVD", name="devise_cvd", methods="GET|POST")
     */
    public function getDeviseCVD(Request $request): Response
    {
        ///////////script de mise à jour des enregistrements anciens//////////
        /*$em=$this->getDoctrine()->getManager();
        $deviseMouvements = $this->getDoctrine()->getRepository(DeviseMouvements::class)->findAll();
        foreach ($deviseMouvements as $deviseMouvement){
            if ($deviseMouvement->getDeviseRecu()) {
                $deviseMouvement->setSoldeOuvByDeviseAndCaisse($deviseMouvement, $deviseMouvement->getJourneeCaisse(), $em);
                $em->persist($deviseMouvement);
            }
        }
            $em->flush();
        dump("success"); die();
*/
        $caisse=$request->request->get('caisse')?$request->request->get('caisse'):$request->query->get('caisse');
        $dateDebut=$request->request->get('dateDebut')?$request->request->get('dateDebut'):$request->query->get('dateDebut');
        $dateFin=$request->request->get('dateFin')?$request->request->get('dateFin'):$request->query->get('dateFin');
        $devise=$request->request->get('devise')?$request->request->get('devise'):$request->query->get('devise');
        $devise=$request->request->get('agence')?$request->request->get('agence'):$request->query->get('agence');
        $uam=$request->request->get('uam')?$request->request->get('uam'):$request->query->get('uam');
        $limit=10000;
        //dump($devise);die();
        $_page=$request->query->get('_page');
        $offset = ($_page)?($_page-1)*$limit:0;
        if (!$_page) {
            $criteresRecherches=new CriteresDates();
            if($dateDebut) $criteresRecherches->setDateDebut(new \DateTime($dateDebut));
            if($dateFin) $criteresRecherches->setDateFin(new \DateTime($dateFin));
        }else{
            $criteresRecherches=new CriteresDates();
            $criteres=$request->query->get('master');
            $criteres= explode ('|',$criteres);
            // dump($criteres);
            // dump($caisse);
            if (count($criteres)==4) {
                $criteresRecherches->setDateDebut(new \DateTime($criteres[0]));
                $criteresRecherches->setDateFin(new \DateTime($criteres[1]));
                $caisse=($caisse)?$caisse:$criteres[2];
                $devise=($devise)?$devise:$criteres[3];
                $ujm=($uam)?$uam:$criteres[4];
            }
        }

        $form = $this->createForm(CriteresRecherchesJourneeCaissesType::class, $criteresRecherches);
        $form->handleRequest($request);

        $dateDebut=new \DateTime($criteresRecherches->getDateDebut()->format('Y-m-d').' 00:00:00');
        $dateFin=new \DateTime($criteresRecherches->getDateFin()->format('Y-m-d').' 23:59:59');
        $result = $this->getDoctrine()
            ->getRepository(DeviseMouvements::class)
            ->getDevisesCVD($caisse,$devise,$dateDebut,$dateFin,$offset,$limit,$uam);
        //dump($deviseJournees);die();
        $pages = round(count($result)/$limit);
//dump($result);die();
        $request->query->set('master',$dateDebut->format('Y-m-d').'|'.$dateFin->format('Y-m-d').'|'.$caisse.'|'.$devise);


        $caisses=$this->getDoctrine()->getRepository(Agences::class)->findAll();
        $devises=$this->getDoctrine()->getRepository(Devises::class)->findAll();
        return $this->render('devise_mouvements/deviseCVD.html.twig', [
            'pages'=>$pages,
            'caisses'=>$caisses,
            'form'=>$form->createView(),
            'caisse_id'=>$caisse,
            'uam'=>$uam,
            'devises'=>$devises,
            'devise_id'=>$devise,
            'path'=>'devise_journees_index',
            'src'=>'orm',
            'mouvement_devises' => $result]);
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

    /*
     * @Route("/new", name="devise_mouvements_new", methods="GET|POST")
     
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
    }*/

    /*
     * @Route("/achatvente", name="devise_mouvements_achatvente", methods="GET|POST")
    
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
*/
    /**
     * @Route("/{id}", name="devise_mouvements_show", methods="GET")
     */
    public function show(DeviseMouvements $deviseMouvement): Response
    {
        return $this->render('devise_mouvements/show.html.twig', ['devise_mouvement' => $deviseMouvement]);
    }

    /*
     * @Route("/{id}/edit", name="devise_mouvements_edit", methods="GET|POST")
     
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
    }*/

    /*
     * @Route("/{id}", name="devise_mouvements_delete", methods="DELETE")
     
    public function delete(Request $request, DeviseMouvements $deviseMouvement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$deviseMouvement->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($deviseMouvement);
            $em->flush();
        }

        return $this->redirectToRoute('devise_mouvements_index');
    }*/

}
