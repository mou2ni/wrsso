<?php

namespace App\Controller;

use App\Entity\Caisses;
use App\Entity\CriteresDates;
use App\Entity\DeviseJournees;
use App\Entity\Devises;
use App\Form\CriteresRecherchesJourneeCaissesType;
use App\Form\DeviseJourneesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/devise/journees")
 */
class DeviseJourneesController extends Controller
{
    /*
    *
     * @
     * Route("/", name="devise_journees_index", methods="GET")
     */
    public function index(): Response
    {
        $deviseJournees = $this->getDoctrine()
            ->getRepository(DeviseJournees::class)
            ->findAll();

        return $this->render('devise_journees/index.html.twig', [
            'src'=>'orm',
            'devise_journees' => $deviseJournees]);
    }



    /**
     * @Route("/", name="devise_journees_index", methods="GET|POST")
     */
    public function getDevise(Request $request): Response
    {

        $caisse=$request->request->get('caisse')?$request->request->get('caisse'):$request->query->get('caisse');
        $dateDebut=$request->request->get('dateDebut')?$request->request->get('dateDebut'):$request->query->get('dateDebut');
        $dateFin=$request->request->get('dateFin')?$request->request->get('dateFin'):$request->query->get('dateFin');
        $devise=$request->request->get('devise')?$request->request->get('devise'):$request->query->get('devise');
        $ujm=$request->request->get('ujm')?$request->request->get('ujm'):$request->query->get('ujm');
        $limit=60;
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
                $ujm=($ujm)?$ujm:$criteres[4];
            }
        }

        $form = $this->createForm(CriteresRecherchesJourneeCaissesType::class, $criteresRecherches);
        $form->handleRequest($request);

        $dateDebut=new \DateTime($criteresRecherches->getDateDebut()->format('Y-m-d').' 00:00:00');
        $dateFin=new \DateTime($criteresRecherches->getDateFin()->format('Y-m-d').' 23:59:59');
        $deviseJournees = $this->getDoctrine()
            ->getRepository(DeviseJournees::class)
            ->getDevises($caisse,$devise,$dateDebut,$dateFin,$offset,$limit,$ujm);
        //dump($deviseJournees);die();
        $pages = round(count($deviseJournees)/$limit);
//dump($pages);die();
        $request->query->set('master',$dateDebut->format('Y-m-d').'|'.$dateFin->format('Y-m-d').'|'.$caisse.'|'.$devise);


        $caisses=$this->getDoctrine()->getRepository(Caisses::class)->findAll();
        $devises=$this->getDoctrine()->getRepository(Devises::class)->findAll();
        return $this->render('devise_journees/index.html.twig', [
            'pages'=>$pages,
            'caisses'=>$caisses,
            'form'=>$form->createView(),
            'caisse_id'=>$caisse,
            'ujm'=>$ujm,
            'devises'=>$devises,
            'devise_id'=>$devise,
            'path'=>'devise_journees_index',
            'src'=>'orm',
            'devise_journees' => $deviseJournees]);
    }
    /**
     * @Route("/ouverture/{id}", name="devise_journees_ouv", methods="GET")
     */
    public function getDeviseOuv(Request $request, Devises $devise): Response
    {
        //dump($devise);die();
        $date = new \DateTime();
        $dateDeb=new \DateTime();
        $dateFin=new \DateTime();
        $debut = $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('d'));
        $fin = $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('d'));
        $limit=10;
        if (($request->query->get('_dateDeb')))
            $debut = new \DateTime($request->query->get('_dateDeb'));
        if ($request->query->get('_dateFin'))
            $fin = new \DateTime($request->query->get('_dateFin'));
        $date = new \DateTime('');
        if ($request->query->get('_date'))$date = new \DateTime($request->query->get('_date'));
        $deviseJournees = $this->getDoctrine()
            ->getRepository(DeviseJournees::class)
            ->getDeviseOuv($debut,$fin,$devise);
        //dump($deviseJournees);die();
        return $this->render('devise_journees/index.html.twig', [
            'src'=>'bd',
            'devise_journees' => $deviseJournees]);
    }
    /**
     * @Route("/achatvente/{id}", name="devise_journees_achat_vente", methods="GET")
     */
    public function getDeviseAchatVente(Request $request, Devises $devise): Response
    {
        //dump();die();
        $date = new \DateTime();
        $dateDeb=new \DateTime();
        $dateFin=new \DateTime();
        $debut = $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('d'));
        $fin = $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('d'));
        $limit=10;
        if (($request->query->get('_dateDeb')))
            $debut = new \DateTime($request->query->get('_dateDeb'));
        if ($request->query->get('_dateFin'))
            $fin = new \DateTime($request->query->get('_dateFin'));
        $date = new \DateTime('');
        if ($request->query->get('_date'))$date = new \DateTime($request->query->get('_date'));
        $deviseJournees = $this->getDoctrine()
            ->getRepository(DeviseJournees::class)
            ->getDeviseAchatVente($debut,$fin,$devise);
        //dump($deviseJournees);die();
        return $this->render('devise_journees/index.html.twig', [
            'src'=>'bd',
            'devise_journees' => $deviseJournees]);
    }
    /**
     * @Route("/fermeture/{id}", name="devise_journees_ferm", methods="GET")
     */
    public function getDeviseFerm(Request $request, Devises $devise): Response
    {
        //dump($devise);die();
        $date = new \DateTime();
        $dateDeb=new \DateTime();
        $dateFin=new \DateTime();
        $debut = $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('d'));
        $fin = $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('d'));
        $limit=10;
        if (($request->query->get('_dateDeb')))
            $debut = new \DateTime($request->query->get('_dateDeb'));
        if ($request->query->get('_dateFin'))
            $fin = new \DateTime($request->query->get('_dateFin'));
        $date = new \DateTime('');
        if ($request->query->get('_date'))$date = new \DateTime($request->query->get('_date'));
        $deviseJournees = $this->getDoctrine()
            ->getRepository(DeviseJournees::class)
            ->getDeviseFerm($debut,$fin,$devise);
        //dump($deviseJournees);die();
        return $this->render('devise_journees/index.html.twig', [
            'src'=>'bd',
            'devise_journees' => $deviseJournees]);
    }
    /**
     * @Route("/cvd/{id}", name="devise_journees_cvd", methods="GET")
     */
    public function getDeviseCvd(Request $request, Devises $devise): Response
    {
        //dump($devise);die();
        $date = new \DateTime();
        $dateDeb=new \DateTime();
        $dateFin=new \DateTime();
        $debut = $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('d'));
        $fin = $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('d'));
        $limit=10;
        if (($request->query->get('_dateDeb')))
            $debut = new \DateTime($request->query->get('_dateDeb'));
        if ($request->query->get('_dateFin'))
            $fin = new \DateTime($request->query->get('_dateFin'));
        $date = new \DateTime('');
        if ($request->query->get('_date'))$date = new \DateTime($request->query->get('_date'));
        $deviseJournees = $this->getDoctrine()
            ->getRepository(DeviseJournees::class)
            ->getDeviseCvd($debut,$fin,$devise);
        //dump($deviseJournees);die();
        /*return $this->render('devise', [
            'src'=>'bd',
            'devise_mouvements' => $deviseJournees]);*/
        return $this->render('devise_mouvements/index.html.twig', [
            'devise_mouvements' => $deviseJournees,
            'journeeCaisse'=>null,
        ]);
    }

    /*
     * @Route("/new", name="devise_journees_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $deviseJournee = new DeviseJournees();
        $form = $this->createForm(DeviseJourneesType::class, $deviseJournee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($deviseJournee);
            $em->flush();

            return $this->redirectToRoute('devise_journees_index');
        }

        return $this->render('devise_journees/new.html.twig', [
            'devise_journee' => $deviseJournee,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="devise_journees_show", methods="GET")
     */
    public function show(DeviseJournees $deviseJournee): Response
    {
        return $this->render('devise_journees/show.html.twig', ['devise_journee' => $deviseJournee]);
    }

    /*
     * @Route("/{id}/edit", name="devise_journees_edit", methods="GET|POST")
     */
    public function edit(Request $request, DeviseJournees $deviseJournee): Response
    {
        $form = $this->createForm(DeviseJourneesType::class, $deviseJournee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            //return $this->redirectToRoute('devise_journees_edit', ['id' => $deviseJournee->getId()]);
        }

        return $this->render('devise_journees/edit.html.twig', [
            'devise_journee' => $deviseJournee,
            'form' => $form->createView(),
        ]);
    }

    /*
     * @Route("/{id}", name="devise_journees_delete", methods="DELETE")
     */
    public function delete(Request $request, DeviseJournees $deviseJournee): Response
    {
        if ($this->isCsrfTokenValid('delete'.$deviseJournee->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($deviseJournee);
            $em->flush();
        }

        return $this->redirectToRoute('devise_journees_index');
    }
}
