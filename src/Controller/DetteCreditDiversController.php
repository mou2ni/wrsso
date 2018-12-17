<?php

namespace App\Controller;

use App\Entity\DetteCreditDivers;
use App\Entity\JourneeCaisses;
use App\Form\CreditType;
use App\Form\DetteCreditDiversType;
use App\Form\DetteType;
use App\Repository\DetteCreditDiversRepository;
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
    /**
     * @Route("/", name="dette_credit_divers_index", methods="GET")
     */
    public function index(): Response
    {
        $detteCreditDivers = $this->getDoctrine()
            ->getRepository(DetteCreditDivers::class)
            ->findAll();

        return $this->render('dette_credit_divers/index.html.twig', ['dette_credit_divers' => $detteCreditDivers]);
    }
    private function detteCredit(Request $request, $dette = true)
    {

        $em = $this->getDoctrine()->getManager();
        $journeeCaisse = ($request->request->get('_journeeCaisse'))?$em->getRepository(JourneeCaisses::class)->find($request->request->get('_journeeCaisse')):null;
        $detteCredit = new DetteCreditDivers($journeeCaisse);
        $form = ($dette)?$this->createForm(DetteType::class, $detteCredit):$this->createForm(CreditType::class, $detteCredit);
        $form->handleRequest($request);
        //dump($form);die();
        if ($form->isSubmitted() && $form->isValid()) {

            $journeeCaisse->addDetteCredit($detteCredit);
            ($dette)?$journeeCaisse->updateM('mDetteDiversFerm',$detteCredit->getMDette()):
            $journeeCaisse->updateM('mCreditDiversFerm',$detteCredit->getMCredit());
            ($dette)?$detteCredit->setStatut(DetteCreditDivers::DETTE_EN_COUR):$detteCredit->setStatut(DetteCreditDivers::CREDIT_EN_COUR);
            $em->persist($journeeCaisse);
            $em->flush();

            if($request->request->has('enregistreretfermer')){
                return $this->redirectToRoute('journee_caisses_gerer');
            }
            $detteCredit = new DetteCreditDivers($journeeCaisse);
            $request = new Request();
            $form = ($dette)?$this->createForm(DetteType::class, $detteCredit):$this->createForm(CreditType::class, $detteCredit);
            $form->handleRequest($request);

        }

        return $this->render('dette_credit_divers/ajout.html.twig', [
            'journeeCaisse'=>$journeeCaisse,
            'form' => $form->createView(),
            'dette'=>$dette
        ]);
    }

    /**
     * @Route("/ad", name="dette_divers", methods="GET|POST|UPDATE")
     */
    public function dette(Request $request): Response
    {
        return $this->detteCredit($request, true);
    }
    /**
     * @Route("/ac", name="credit_divers", methods="GET|POST|UPDATE")
     */
    public function credit(Request $request): Response
    {
        return $this->detteCredit($request, false);
    }

    /**
     * @Route("/rembourser/{id}", name="dette_credit_divers_rembourser", methods="GET|POST")
     */
    public function rembourser(Request $request, DetteCreditDivers $detteCreditDiver): Response
    {

        $em = $this->getDoctrine()->getManager();
        if ($detteCreditDiver->getStatut()==DetteCreditDivers::DETTE_EN_COUR){
            $detteCreditDiver->setMCredit($detteCreditDiver->getMDette());
            //$detteCreditDiver->getJourneeCaisse()->setMDetteDiversFerm($detteCreditDiver->getJourneeCaisse()->getMDetteDiversFerm()-$detteCreditDiver->getMDette());
            $detteCreditDiver->setStatut(DetteCreditDivers::DETTE_REMBOURSE);
        }
        else{
            $detteCreditDiver->setMDette($detteCreditDiver->getMCredit());
            //$detteCreditDiver->getJourneeCaisse()->setMCreditDiversFerm($detteCreditDiver->getJourneeCaisse()->getMCreditDiversFerm()-$detteCreditDiver->getMCredit());
            $detteCreditDiver->setStatut(DetteCreditDivers::CREDIT_REMBOURSE);

        }

        $detteCreditDiver->setDateRemboursement(new \DateTime('now'));
        $detteCreditDiver->setUtilisateurRemboursement($detteCreditDiver->getJourneeCaisse()->getUtilisateur());
        //$detteCreditDiver->getJourneeCaisse()->maintenirDetteCreditDiversFerm();
        $detteCreditDiver->getJourneeCaisse()->setMCreditDiversFerm($this->getTotalCredits($detteCreditDiver->getJourneeCaisse()));
        $detteCreditDiver->getJourneeCaisse()->setMDetteDiversFerm($this->getTotalDettes($detteCreditDiver->getJourneeCaisse()));
        $em->persist($detteCreditDiver);
        $em->flush();
        //dump($detteCreditDiver);die();

        return $this->redirectToRoute('dette_credit_divers', ['id'=>$detteCreditDiver->getJourneeCaisse()->getId()]);
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
     * @Route("/{id}", name="dette_credit_divers_show", methods="GET")
     */
    public function show(DetteCreditDivers $detteCreditDiver): Response
    {
        return $this->render('dette_credit_divers/show.html.twig', ['dette_credit_diver' => $detteCreditDiver]);
    }

    /**
     * @Route("/{id}/edit", name="dette_credit_divers_edit", methods="GET|POST")
     */
    public function edit(Request $request, DetteCreditDivers $detteCreditDiver): Response
    {
        $form = $this->createForm(DetteCreditDiversType::class, $detteCreditDiver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dette_credit_divers_edit', ['id' => $detteCreditDiver->getId()]);
        }

        return $this->render('dette_credit_divers/edit.html.twig', [
            'dette_credit_diver' => $detteCreditDiver,
            'form' => $form->createView(),
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
