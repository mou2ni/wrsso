<?php

namespace App\Controller;

use App\Entity\Billetages;
use App\Entity\Billets;
use App\Entity\Caisses;
use App\Entity\DeviseJournees;
use App\Entity\JourneeCaisses;
use App\Entity\SystemElectInventaires;
use App\Entity\SystemElects;
use App\Entity\Utilisateurs;
use App\Form\BilletagesType;
use App\Form\DeviseJourneesType;
use App\Form\FermetureType;
use App\Form\JourneeCaissesType;
use App\Form\OuvertureFermetureType;
use App\Form\OuvertureType;
use App\Utils\GenererCompta;
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
     * @Route("/new", name="journee_caisses_new", methods="GET|POST|UPDATE")
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
     * @Route("/ouvrir", name="journee_caisses_ouvrir", methods="GET|POST|UPDATE")
     */
    public function ouvrir(Request $request): Response
    {
        //if ($request->getMethod()=='UPDATE')
          //  dump($request);die();

        $utilisateur=$this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['login'=>'login']);
        if(!$utilisateur->getEstcaissier()){
            $this->addFlash('success', "vous n'etes pas Caissier? munissez vous des droits necessaires puis reessayez");
            return $this->render('main.html.twig'
            );
        }
        if ($utilisateur->getJourneeCaisseActive()->getStatut() ==JourneeCaisses::INITIAL) {
            //si l'utilisateur a choisi une caisse et cliquer sur 'initialise' retrouver ou attribuer une nouvelle journee caisse
            if ($request->query->has('initialise')) {
                $caisse = $request->get('caisse');
                $journeeCaisse = $caisse->getNouvelleJournee();
                $utilisateur->setLastCaisse($caisse);
            } else {
                $journeeCaisse = $utilisateur->getJourneeCaisseActive();
            }

            //si l'utilisateur n'a pas de caisse active et n'a pas demander une initialisation (Chargement nouvelle de la page)
            if (!$journeeCaisse) {
                $caisse = $utilisateur->getLastCaisse();
                $journeeCaisse = $caisse->getNouvelleJournee();
                $journeeCaisse->setUtilisateur($utilisateur);
                $journeeCaisse->setCaisse($caisse);
                $utilisateur->setJourneeCaisseActive($journeeCaisse);
            }


            $form = $this->createForm(OuvertureType::class, $journeeCaisse);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                //dump($form->getClickedButton()->getName());die();
                $em = $this->getDoctrine()->getManager();
                $genererCompta = new GenererCompta($this->getDoctrine()->getManager());
                $journeeCaisse->setStatut(JourneeCaisses::OUVERT);
                $em->persist($journeeCaisse);
                if (!$genererCompta->genComptaEcart($utilisateur, $journeeCaisse->getCaisse(), 'Ecart ouverture' . $journeeCaisse, $journeeCaisse->getMEcartOuv())) return $this->render('comptMainTest.html.twig', ['transactions' => [$genererCompta->getTransactions()]]);
                $em->flush();

                return $this->redirectToRoute('journee_caisses_index');
            }

            return $this->render('journee_caisses/ouvrir.html.twig', [
                'journeePrecedente' => $journeeCaisse->getJourneePrecedente(),
                'form' => $form->createView(),
                'journeeCaisse' => $journeeCaisse,
            ]);
        }
        else
        {
            $this->addFlash('success', "Votre Caisse est deja ou toujours ouverte");
            return $this->redirectToRoute('journee_caisses_index');
        }

    }

    /**
     * @Route("/enregistrer", name="journee_caisses_enregistrer", methods="GET|POST|UPDATE")
     */
    public function enregistrer(Request $request){
        $utilisateur=$this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['login'=>'login']);

        $em=$this->getDoctrine()->getManager();
        $operation=$request->request->get('_operation');
        $journeeCaisse=$em->getRepository('App:JourneeCaisses')->find($request->request->get('_journeeCaisse'));
        if ($operation=="OUVRIR"){

            $em = $this->getDoctrine()->getManager();
            $genererCompta = new GenererCompta($this->getDoctrine()->getManager());
            $journeeCaisse->setStatut(JourneeCaisses::OUVERT);
            $em->persist($journeeCaisse);
            if (!$genererCompta->genComptaEcart($utilisateur, $journeeCaisse->getCaisse(), 'Ecart ouverture' . $journeeCaisse, $journeeCaisse->getMEcartOuv())) return $this->render('comptMainTest.html.twig', ['transactions' => [$genererCompta->getTransactions()]]);
            $em->flush();

            return $this->redirectToRoute('journee_caisses_gerer');
        }
        else{
            $journeeCaisse->setStatut(JourneeCaisses::FERME);
            $newJournee = $journeeCaisse->preparerOuverture();
            $utilisateur=$newJournee->getUtilisateur();
            $utilisateur->setJourneeCaisseActive($newJournee);
            $em->persist($journeeCaisse);
            $em->persist($newJournee);
            $em->persist($utilisateur);
            $em->flush();
            return $this->redirectToRoute('journee_caisses_gerer');
        }



    }

    /**
     * @Route("/gerer", name="journee_caisses_gerer", methods="GET|POST|UPDATE")
     */
    public function gerer(Request $request): Response
    {
        $utilisateur=$this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['login'=>'login']);
        if(!$utilisateur->getEstcaissier()){
            $this->addFlash('success', "vous n'etes pas Caissier? munissez vous des droits necessaires puis reessayez");
            return $this->render('main.html.twig'
            );
        }
        if ($request->query->has('submit')){
            dump($request->query());die();
        }
        if ($utilisateur->getJourneeCaisseActive()->getStatut() == JourneeCaisses::OUVERT) {
            $journeeCaisse = $utilisateur->getJourneeCaisseActive();
            $form = $this->createForm(FermetureType::class, $journeeCaisse);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                dump($journeeCaisse);die();
            }
        return $this->render('journee_caisses/gerer.html.twig', [
            //'journeePrecedente' => $journeeCaisse->getJourneePrecedente(),
            'form' => $form->createView(),
            'journeeCaisse' => $journeeCaisse,
        ]);
        }
        else
        {
            $this->addFlash('success', "Votre Caisse est deja ou toujours ouverte");
            return $this->redirectToRoute('journee_caisses_index');
        }


    }

    /**
     * @Route("/ouverture", name="journee_caisses_ouverture", methods="GET|POST")
     */
    public function ouverture(Request $request): Response
    {

        $em = $this->getDoctrine()->getManager();
        $journeeCaisse=$this->get('session')->get('journeeCaisse');
        $user=$journeeCaisse->getUtilisateur();
        $caisse=$journeeCaisse->getCaisse();

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
            $journeeCaissePrec = $this->getJourneeCaissePrec($caisse);
            $devises=$em->getRepository('App:Devises')->findAll();
            $journeeCaiss = new JourneeCaisses();
            $journeeCaiss=$this->initJournee($journeeCaiss);
            $form = $this->createForm(OuvertureType::class, $journeeCaiss);
            $form->handleRequest($request);

            if($request->request->get('billetageOuv')){
                $billets=$this->getDoctrine()->getRepository(Billets::class)->findAll();
                $this->render('billetages/ajout.html.twig', [
                    'billets' => $billets,
                    'form' => $form->createView(),
                ]);
            }
            if ($form->isSubmitted() && $form->isValid()) {
                if($form->getClickedButton()->getName()=="billetageOuv"){
                    $billetage = $this->getDoctrine()->getRepository(Billetages::class)->find(26);
                    $form1 = $this->createForm(BilletagesType::class, $billetage);
                    $form1->handleRequest($request);
                    $billets=$this->getDoctrine()->getRepository(Billets::class)->findAll();
                    return $this->render('billetages/ajout.html.twig', [
                        'billets' => $billets,
                        'form' => $form1->createView(),
                    ]);
                }
                $em = $this->getDoctrine()->getManager();
                //dump($journeeCaiss);die();
                $em->persist($journeeCaiss);
                $em->flush();

                return $this->redirectToRoute('journee_caisses_index');
            }

            return $this->render('journee_caisses/ouverture.html.twig', [
                'devises' => $devises,
                'form' => $form->createView(),
                'journeePrecedente'=>$journeeCaissePrec
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
        $journecaisse = $em->getRepository('App:JourneeCaisses')->findBy(array('utilisateur'=>$user, "statut"=>"O"));
        if ($journecaisse){

            return true;
        }
        else
            return false;
    }

    public function initJournee1(JourneeCaisses $journeeCaiss, Caisses $caisse, Utilisateurs $user)
    {
        if(!$this->get('session')->get('billetage'))
        {
            $billetage0 = $this->getDoctrine()->getRepository(Billetages::class)->find(1);
            $billetages= array($billetage0,$billetage0,$billetage0);
            $this->get('session')->set('billetage',$billetages);
        }
        $journeeCaiss->setUtilisateur($user);
        $journeeCaiss->setIdCaisse($caisse);
        $journeeCaiss->setStatut('O');
        $journeeCaissePrec=$this->getJourneeCaissePrec($caisse);
        $journeeCaiss->setMCreditDivers($journeeCaissePrec->getMCreditDivers());
        $journeeCaiss->setMDetteDivers($journeeCaissePrec->getMDetteDivers());
        if($this->get('session')->get('billetage') && $this->get('session')->get('billetage')!=$this->getDoctrine()->getRepository(Billetages::class)->find(1)){
            $journeeCaiss->setIdBilletOuv($this->get('session')->get('billetage')[0]);
            $journeeCaiss->setValeurBillet($this->get('session')->get('billetage')[0]->getValeurTotal());
            $this->getDoctrine()->getManager()->persist($this->get('session')->get('billetage')[0]);
        }
        if($this->get('session')->get('electronic')!=$this->getDoctrine()->getRepository(SystemElectInventaires::class)->find(1)){
            $journeeCaiss->setIdSystemElectInventOuv($this->get('session')->get('electronic'));
            $journeeCaiss->setSoldeElectOuv($this->get('session')->get('electronic')->getSoldeTotal());
            $this->getDoctrine()->getManager()->persist($this->get('session')->get('electronic'));
        }
        //$journeeCaiss->setSoldeElectOuv($this->get('session')->get('electronic')->getSoldeTotal());
        $journeeCaiss->setDateOuv(new \DateTime('now'));
        $journeeCaiss->setDateFerm(new \DateTime('now'));
        //$journeeCaiss->setDeviseJournee($this->contreValeurDevise($journeeCaissePrec));
        return $journeeCaiss;
    }

    public function contreValeurDevise(JourneeCaisses $journeeCaisses){
        $list=$journeeCaisses->getDeviseJournee();
        return $list;
    }

    public function initJournee(JourneeCaisses $journeeCaisses)
    {

        $journeeCaisses=$this->get('session')->get('journeeCaisse');
        if($this->get('session')->get('billetage') && $this->get('session')->get('billetage')!=$this->getDoctrine()->getRepository(Billetages::class)->find(1)){
            $journeeCaisses->setIdBilletOuv($this->get('session')->get('billetage')[0]);
            $journeeCaisses->setValeurBillet($this->get('session')->get('billetage')[0]->getValeurTotal());
            $this->getDoctrine()->getManager()->persist($this->get('session')->get('billetage')[0]);
        }
        if($this->get('session')->get('electronic')!=$this->getDoctrine()->getRepository(SystemElectInventaires::class)->find(1)){
            $journeeCaisses->setIdSystemElectInventOuv($this->get('session')->get('electronic'));
            $journeeCaisses->setSoldeElectOuv($this->get('session')->get('electronic')->getSoldeTotal());
            $this->getDoctrine()->getManager()->persist($this->get('session')->get('electronic'));
        }
        //$journeeCaiss->setSoldeElectOuv($this->get('session')->get('electronic')->getSoldeTotal());
        $journeeCaisses->setDateOuv(new \DateTime('now'));
        $journeeCaisses->setDateFerm(new \DateTime('now'));

        return $journeeCaisses;


    }

}
