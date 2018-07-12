<?php

namespace App\Controller;

use App\Entity\Billetages;
use App\Entity\Caisses;
use App\Entity\JourneeCaisses;
use App\Entity\SystemElectInventaires;
use App\Entity\SystemElects;
use App\Entity\Utilisateurs;
use App\Form\JourneeCaissesType;
use App\Form\OuvertureType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/journee/caisses")
 */
class JourneeCaissesController extends Controller
{
    /**
     * @Route("/", name="journee_caisses_index", methods="GET")
     */
    public function index(): Response
    {
        $journeeCaisses = $this->getDoctrine()
            ->getRepository(JourneeCaisses::class)
            ->findAll();

        return $this->render('journee_caisses/index.html.twig', ['journee_caisses' => $journeeCaisses]);
    }

    /**
     * @Route("/new", name="journee_caisses_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $journeeCaiss = new JourneeCaisses();
        $form = $this->createForm(JourneeCaissesType::class, $journeeCaiss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($journeeCaiss);
            $em->flush();

            return $this->redirectToRoute('journee_caisses_index');
        }

        return $this->render('journee_caisses/new.html.twig', [
            'journee_caiss' => $journeeCaiss,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ouverture", name="journee_caisses_ouverture", methods="GET|POST")
     */
    public function ouverture(Request $request): Response
    {

        $em = $this->getDoctrine()->getManager();
        $caisse=$em->getRepository('App:Caisses')->find(1);
        $user=$em->getRepository('App:Utilisateurs')->find(2);
        if (!$user->getEstCaissier()) {
            $this->addFlash('success', "vous n'etes pas Caissier? munissez vous des droits necessaires puis reessayez");
            return $this->redirectToRoute('journee_caisses_index');
        } elseif ($this->journeeCaisseEnCours($user))
        {
            $this->addFlash('success', "vous avez une caisse toutjours en cours. veuillez la fermer dabord");
            return $this->redirectToRoute('journee_caisses_index');
        }
        else{


            if(!$this->get('session')->get('electronic'))$this->get('session')->set('electronic', new SystemElectInventaires());
            //$caisse=$em->getRepository('App:JourneeCaisses')->findBy(['statut'=>'F'])->getIdCaisse();
            $journeeCaissePrec = $this->getJourneeCaissePrec($caisse);
            $journeeCaiss = new JourneeCaisses();
            $journeeCaiss=$this->initJournee($journeeCaiss, $caisse,$user);
            //dump($journeeCaiss);die();
            $form = $this->createForm(OuvertureType::class, $journeeCaiss);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($journeeCaiss);
                $em->flush();

                return $this->redirectToRoute('journee_caisses_index');
            }

            return $this->render('journee_caisses/ouverture.html.twig', [
                //'journee_caiss' => $journeeCaiss,
                'form' => $form->createView(),
                'journeeCaissePrec'=>$journeeCaissePrec
            ]);
        }

    }


    /**
     * @Route("/{id}", name="journee_caisses_show", methods="GET")
     */
    public function show(JourneeCaisses $journeeCaiss): Response
    {
        return $this->render('journee_caisses/show.html.twig', ['journee_caiss' => $journeeCaiss]);
    }

    /**
     * @Route("/{id}/edit", name="journee_caisses_edit", methods="GET|POST")
     */
    public function edit(Request $request, JourneeCaisses $journeeCaiss): Response
    {
        $form = $this->createForm(JourneeCaissesType::class, $journeeCaiss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('journee_caisses_edit', ['id' => $journeeCaiss->getId()]);
        }

        return $this->render('journee_caisses/edit.html.twig', [
            'journee_caiss' => $journeeCaiss,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="journee_caisses_delete", methods="DELETE")
     */
    public function delete(Request $request, JourneeCaisses $journeeCaiss): Response
    {
        if ($this->isCsrfTokenValid('delete'.$journeeCaiss->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($journeeCaiss);
            $em->flush();
        }

        return $this->redirectToRoute('journee_caisses_index');
    }

    public function getJourneeCaissePrec(Caisses $caisse)
    {
        $journeeCaissePrec = $this->getDoctrine()
            ->getRepository(JourneeCaisses::class)
            ->findOneBy(['idCaisse'=>$caisse, 'idJourneeSuivante'=>null, 'statut'=>'F']);
        if($journeeCaissePrec)
        return $journeeCaissePrec;
        else return $journeeCaissePrec=new JourneeCaisses();
    }

    public function journeeCaisseEnCours(Utilisateurs $user){
        $em = $this->getDoctrine()->getManager();
        $journecaisse = $em->getRepository('App:JourneeCaisses')->findBy(array('idUtilisateur'=>$user, "statut"=>"O"));
        if ($journecaisse){

            return true;
        }
        else
            return false;
    }

    public function recuperationBilletageOuv(){
        $param=$this->get('session')->get('param');
        if ($param['classe']=='j' && $param['champ']=='o'&& $this->get('session')->get('billetage'))
            $billetage=$this->get('session')->get('billetage');
        else $billetage= $this->getDoctrine()->getRepository( Billetages::class)->find(1);

        return $billetage;
    }

    public function recuperationBilletageFerm(){
        $param=$this->get('session')->get('param');
        if ($param['classe']=='j' && $param['champ']=='f'&& !$this->get('session')->get('billetage'))
            $billetage=$this->get('session')->get('billetage')->getValeurTotal();
        else $billetage=new Billetages();

        return $billetage;
    }

    public function initJournee(JourneeCaisses $journeeCaiss, Caisses $caisse, Utilisateurs $user)
    {
        $journeeCaiss->setIdUtilisateur($user);
        $journeeCaiss->setIdCaisse($caisse);
        $journeeCaiss->setStatut('O');
        $journeeCaiss->setMCreditDivers($this->getJourneeCaissePrec($caisse)->getMCreditDivers());
        $journeeCaiss->setMDetteDivers($this->getJourneeCaissePrec($caisse)->getMDetteDivers());
        $journeeCaiss->setIdBilletOuv($this->recuperationBilletageOuv());
        $journeeCaiss->setValeurBillet($this->recuperationBilletageOuv()->getValeurTotal());
        $journeeCaiss->setSoldeElectOuv($this->get('session')->get('electronic')->getSoldeTotal());
        $journeeCaiss->setDateOuv(new \DateTime('now'));
        $journeeCaiss->setDateFerm(new \DateTime('now'));
        return $journeeCaiss;
    }

}
