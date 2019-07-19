<?php

namespace App\Controller;

use App\Entity\DetteCreditDivers;
use App\Entity\DeviseJournees;
use App\Entity\InterCaisses;
use App\Entity\JourneeCaisses;
use App\Form\CreditType;
use App\Form\DetteCreditDiversType;
use App\Form\DetteType;
use App\Repository\DetteCreditDiversRepository;
use App\Utils\SessionUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @Route("/dette/credit/divers")
 */
class DetteCreditDiversController extends Controller
{
    private $journeeCaisse;
    private $utilisateur;

    public function __construct(SessionUtilisateur $sessionUtilisateur)
    {
        $this->utilisateur=$sessionUtilisateur->getUtilisateur();
        $this->journeeCaisse=$sessionUtilisateur->getJourneeCaisse();
        if(!$this->journeeCaisse){
            return $this->redirectToRoute('app_login');
        }
    }
    /**
     * @Route("/", name="dette_credit_divers_index", methods="GET")
     */
    public function index(): Response
    {
        $detteCreditDivers = $this->getDoctrine()
            ->getRepository(DetteCreditDivers::class)
            ->liste();

        return $this->render('dette_credit_divers/index.html.twig', ['dette_credit_divers' => $detteCreditDivers]);
    }

    /**
     * @Route("/{id}/edit", name="dette_credit_divers_edit", methods="GET|POST")
     */
    public function edit(Request $request, DetteCreditDivers $detteCreditDiver): Response
    {
        ($detteCreditDiver->getStatut()== DetteCreditDivers::CREDIT_EN_COUR)?
            $detteCreditDiver->setMSaisie($detteCreditDiver->getMCredit()):
            $detteCreditDiver->setMSaisie($detteCreditDiver->getMDette());

        $form = $this->createForm(DetteCreditDiversType::class, $detteCreditDiver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($detteCreditDiver->getStatut()== DetteCreditDivers::CREDIT_EN_COUR){
                $detteCreditDiver->setMCredit($detteCreditDiver->getMSaisie());
                //$this->journeeCaisse->updateM('mCreditDiversFerm',$detteCreditDiver->getMCredit());
                }
            else
            {
                $detteCreditDiver->setMDette($detteCreditDiver->getMSaisie());
                //$this->journeeCaisse->updateM('mDetteDiversFerm',$detteCreditDiver->getMDette());
            }
            $this->journeeCaisse->maintenirDetteCreditDiversFerm();
            $this->getDoctrine()->getManager()->flush();

            //return $this->redirectToRoute('dette_credit_divers_edit', ['id' => $detteCreditDiver->getId(),]);
            if($request->request->has('enregistreretfermer')){
                return $this->redirectToRoute('journee_caisses_gerer');
            }
            return $this->redirectToRoute('detteCredits_divers');

        }

        return $this->render('dette_credit_divers/edit.html.twig', [
            'dette_credit_diver' => $detteCreditDiver,
            'form' => $form->createView(),
            'journeeCaisse'=>$detteCreditDiver->getJourneeCaisseActive()
        ]);
    }


    /**
     * @Route("/dettecredits", name="detteCredits_divers", methods="GET|POST|UPDATE")
     */
    public function detteCredit(Request $request)
    {
        if($this->journeeCaisse->getStatut()!=JourneeCaisses::ENCOURS or
            $this->utilisateur->getId()!=$this->journeeCaisse->getUtilisateur()->getId()){
            $this->addFlash('error','Aucune journée ouverte. Merci d\'ouvrir une journée avant de continuer');
            return $this->redirectToRoute('journee_caisses_gerer');
        }
        $em = $this->getDoctrine()->getManager();
        $detteCredit = new DetteCreditDivers();
        $detteCredit->setUtilisateurCreation($this->utilisateur);
        $form = $this->createForm(DetteCreditDiversType::class, $detteCredit);
        $form->handleRequest($request);

        //dump($form);die();
        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->journeeCaisse->addDetteCredit($detteCredit);

            $em->persist($this->journeeCaisse);
            $em->flush();

            if($request->request->has('enregistreretfermer')){
                return $this->redirectToRoute('journee_caisses_gerer');
            }
            $detteCredit = new DetteCreditDivers($this->journeeCaisse);
            $request = new Request();
            //$form = ($dette)?$this->createForm(DetteType::class, $detteCredit):$this->createForm(CreditType::class, $detteCredit);
            $detteCredit->setUtilisateurCreation($this->utilisateur);
            $form = $this->createForm(DetteCreditDiversType::class, $detteCredit);
            $form->handleRequest($request);

        }

        $myDettes=$this->getDoctrine()->getRepository(DetteCreditDivers::class)->getDettesEncours($this->journeeCaisse);
        $myCredits=$this->getDoctrine()->getRepository(DetteCreditDivers::class)->getCreditsEncours($this->journeeCaisse);


        return $this->render('dette_credit_divers/ajout.html.twig', [
            'journeeCaisse'=>$this->journeeCaisse,
            'form' => $form->createView(),
            'myDettes'=>$myDettes,
            'myCredits'=>$myCredits,
        ]);
    }

    /*
     * @Route("/ad", name="dette_divers", methods="GET|POST|UPDATE")
     */
    public function dette(Request $request): Response
    {
        return $this->detteCredit($request, true);
    }
    /*
     * @Route("/ac", name="credit_divers", methods="GET|POST|UPDATE")
     */
    public function credit(Request $request): Response
    {
        return $this->detteCredit($request, false);
    }

    /**
     * @Route("/{id}/rembourser", name="dette_credit_divers_rembourser", methods="GET|POST")
     */
    public function rembourser(Request $request, DetteCreditDivers $detteCreditDiver): Response
    {

        $em = $this->getDoctrine()->getManager();
        //$dette = true;
        if ($detteCreditDiver->getStatut()==DetteCreditDivers::DETTE_EN_COUR){
            $detteCreditDiver->setMCredit($detteCreditDiver->getMDette());
            //$detteCreditDiver->getJourneeCaisse()->setMDetteDiversFerm($detteCreditDiver->getJourneeCaisse()->getMDetteDiversFerm()-$detteCreditDiver->getMDette());
            $detteCreditDiver->setStatut(DetteCreditDivers::DETTE_REMBOURSE);
            $this->journeeCaisse->updateM('mDetteDiversFerm', - $detteCreditDiver->getMDette());
        }
        else{
            $detteCreditDiver->setMDette($detteCreditDiver->getMCredit());
            //$detteCreditDiver->getJourneeCaisse()->setMCreditDiversFerm($detteCreditDiver->getJourneeCaisse()->getMCreditDiversFerm()-$detteCreditDiver->getMCredit());
            $detteCreditDiver->setStatut(DetteCreditDivers::CREDIT_REMBOURSE);
            $dette = false;
            $this->journeeCaisse->updateM('mCreditDiversFerm', - $detteCreditDiver->getMCredit());


        }

        $detteCreditDiver->setDateRemboursement(new \DateTime('now'));
        $detteCreditDiver->setUtilisateurRemboursement($this->journeeCaisse->getUtilisateur());
        //$detteCreditDiver->getJourneeCaisse()->maintenirDetteCreditDiversFerm();
        //$this->journeeCaisse->setMCreditDiversFerm($this->getTotalCredits($detteCreditDiver->getJourneeCaisse()));
        //$this->journeeCaisse->setMDetteDiversFerm($this->getTotalDettes($detteCreditDiver->getJourneeCaisse()));
        //$em->persist($detteCreditDiver);
        $em->persist($this->journeeCaisse);
        $em->flush();
        //dump($detteCreditDiver);die();
        //return $this->detteCredit($request, $dette);
        //return ($dette)?$this->redirectToRoute('dette_divers'):$this->redirectToRoute('credit_divers');
        return $this->redirectToRoute('detteCredits_divers');
    }

    /**
     * @Route("/new", name="dette_credit_divers_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $detteCreditDiver = new DetteCreditDivers();
        $form = $this->createForm(DetteCreditDiversType::class, $detteCreditDiver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($detteCreditDiver);
            $em->flush();

            return $this->redirectToRoute('dette_credit_divers_index');
        }

        return $this->render('dette_credit_divers/new.html.twig', [
            'dette_credit_diver' => $detteCreditDiver,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dette_credit_divers_show", methods="GET|POST")
     */
    public function show(JourneeCaisses $journeeCaisse): Response
    {
        //dump($journeeCaisse);die();
        $myDettes=$this->getDoctrine()->getRepository(DetteCreditDivers::class)->getDettesEncours($journeeCaisse);
        $myCredits=$this->getDoctrine()->getRepository(DetteCreditDivers::class)->getCreditsEncours($journeeCaisse);
        return $this->render('dette_credit_divers/liste.html.twig', [
            'journeeCaisse'=>$journeeCaisse,
            'myDettes'=>$myDettes,
            'myCredits'=>$myCredits,
        ]);
    }
    /**
     * @Route("/{id}/detail", name="dette_credit_divers_detail", methods="GET|POST")
     */
    public function detail(DetteCreditDivers $detteCreditDiver): Response
    {
        return $this->render('dette_credit_divers/show.html.twig', [
            'dette_credit_diver'=>$detteCreditDiver,
        ]);
    }


    /**
     * @Route("/{id}", name="dette_credit_divers_delete", methods="DELETE")
     */
    public function delete(Request $request, DetteCreditDivers $detteCreditDiver): Response
    {
        if ($this->isCsrfTokenValid('delete'.$detteCreditDiver->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($detteCreditDiver);
            $em->flush();
        }

        return $this->redirectToRoute('dette_credit_divers_index');
    }

    public function getTotalDettes(JourneeCaisses $journeeCaisse){
        //dump($journeeCaisse);die();
        $total=0;
        foreach ($journeeCaisse->getDetteCredits() as $dc){
            //dump($dc);die();
            if ($dc->getStatut()==DetteCreditDivers::DETTE_EN_COUR)
            $total=$total + $dc->getMDette();
        }
        return $total;
    }

    public function getTotalCredits(JourneeCaisses $journeeCaisse){
        $total=0;
        foreach ($journeeCaisse->getDetteCredits() as $dc){
            if ($dc->getStatut()==DetteCreditDivers::CREDIT_EN_COUR)
            $total=$total + $dc->getMCredit();
        }
        return $total;
    }
}
