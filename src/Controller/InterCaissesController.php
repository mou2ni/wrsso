<?php

namespace App\Controller;

use App\Entity\Caisses;
use App\Entity\InterCaisses;
use App\Entity\JourneeCaisses;
use App\Entity\ParamComptables;
use App\Form\InterCaissesJourneeType;
use App\Form\InterCaissesType;
use App\Form\JourneeCaissesType;
use App\Repository\IntercaissesRepository;
use App\Utils\GenererCompta;
use App\Utils\SessionUtilisateur;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inter/caisses")
 */
class InterCaissesController extends Controller
{
    public $totalE=0;
    public $totalR=0;

    private $journeeCaisse;
    private $utilisateur;
    private $caisse;

    public function __construct(SessionUtilisateur $sessionUtilisateur)
    {
        $this->utilisateur=$sessionUtilisateur->getUtilisateur();
        $this->caisse=$sessionUtilisateur->getLastCaisse();
        $this->journeeCaisse=$sessionUtilisateur->getJourneeCaisse();
        if(!$this->journeeCaisse){
            return $this->redirectToRoute('app_login');
        }
    }
    /**
     * @Route("/", name="inter_caisses_index", methods="GET")
     */
    public function index(): Response
    {
        $interCaisses = $this->getDoctrine()
            ->getRepository(InterCaisses::class)
            ->findAll();

        return $this->render('inter_caisses/index.html.twig', ['inter_caisses' => $interCaisses]);
    }

    /**
     * @Route("/ajout", name="intercaisses_ajout", methods="GET|POST|UPDATE")
     */
    public function ajout(Request $request): Response
    {

        $em =$this->getDoctrine();
        $operation=$request->request->get('_operation');
        $interCaiss = new InterCaisses();
        //$interCaiss->setJourneeCaisseEntrant($this->journeeCaisse)->setStatut($interCaiss::INITIE);
        $interCaiss->setStatut($interCaiss::INITIE);
        $form = $this->createForm(InterCaissesType::class, $interCaiss,['dateComptable'=>$this->journeeCaisse->getDateComptable(),'myJournee'=>$this->journeeCaisse]);
        $form->handleRequest($request);
        //$this->totalInterCaisse($this->journeeCaisses);
        //$this->journeeCaisses->setMIntercaisses($this->totalE-$this->totalR);


        if ($form->isSubmitted() && $form->isValid()) {
            //dump($interCaiss);die();

            /*if($interCaiss->isSortant()){
                $interCaiss->setJourneeCaisseEntrant($interCaiss->getJourneeCaissePartenaire());
                $interCaiss->setJourneeCaisseSortant($this->journeeCaisse);
            }else{ //entrant : autovalider
                $interCaiss->setJourneeCaisseEntrant($this->journeeCaisse);
                $interCaiss->setJourneeCaisseSortant($interCaiss->getJourneeCaissePartenaire());
                $interCaiss=$this->valider($interCaiss, InterCaisses::VALIDATION_AUTO);
            }*/
            $interCaiss->setJourneeCaisseEntrant($this->journeeCaisse);
            //$interCaiss->setJourneeCaisseSortant($interCaiss->getJourneeCaissePartenaire());
            
            if ($interCaiss->getMIntercaisse()>0) $interCaiss=$this->valider($interCaiss, InterCaisses::VALIDATION_AUTO);
            //$operation=$request->request->get('_operation');
            $this->getDoctrine()->getManager()->persist($interCaiss);
            $this->getDoctrine()->getManager()->flush();

            if($request->request->has('enregistreretfermer')){
                return $this->redirectToRoute('journee_caisses_gerer');
            }
            return $this->redirectToRoute('intercaisses_ajout');
            /*$interCaiss = new InterCaisses();
            $request = new Request();
            //dump($interCaiss);
            $interCaiss->setStatut($interCaiss::INITIE);
            $form = $this->createForm(InterCaissesType::class, $interCaiss,['dateComptable'=>$this->journeeCaisse->getDateComptable(),'myJournee'=>$this->journeeCaisse]);
            $form->handleRequest($request);*/

        }
/*
        if($request->isXmlHttpRequest()){
            $em=$this->getDoctrine()->getManager();
            if($interCaiss = $request->request->get('intercaisse')) {
                $statut = $interCaiss[1];// . $interCaiss[2];
                $idIntercaisse = $interCaiss[0];
                $statut = substr($interCaiss,-1);
                $idIntercaisse = substr($interCaiss,0,-1);
                $interCaisse = $em->getRepository("App:InterCaisses")->find($idIntercaisse);
                if ($interCaisse->getStatut() == InterCaisses::INITIE)
                {
                    if ($statut=='V'){
                        $interCaisse->valider();
                        //$caissePartenaire = ($this->caisse == $interCaisse->getJourneeCaisseEntrant())?$interCaisse->getJourneeCaisseEntrant()->getCaisse():
                        //    $interCaisse->getJourneeCaisseSortant()->getCaisse();
                        $genCompta=new GenererCompta($em);
                        $genCompta->genComptaIntercaisse($this->utilisateur,$interCaisse->getJourneeCaisseEntrant()->getCaisse(), $interCaisse->getJourneeCaisseSortant()->getCaisse(),$interCaisse->getMIntercaisse());
                    }
                    elseif($statut=='X') $interCaisse->setStatut(InterCaisses::ANNULE);

                    $em->persist($interCaisse);
                    $em->flush();
                    $interCaiss=[
                        ['id'=>$interCaisse]
                    ];

                    $data = ["intercaisse"=>$interCaisse->getId()];
                }
                else $data = ["intercaisse"=>0];
                return new JsonResponse($data);
                //dump($interCaiss);die();
                //$this->journeeCaisses->setMIntercaisses($this->totalR-$this->totalE);
            }
            if ($request->request->get('valider')){
                foreach ($this->journeeCaisse->getIntercaisseSortants() as $intercaisseSortant ){
                    if ($intercaisseSortant->getStatut()== InterCaisses::INITIE){
                        $intercaisseSortant->valider();
                    }
                }
                $em->persist($this->journeeCaisse);
                $em->flush();
                dump($this->journeeCaisse);die();

            }



        }

*/
        //dump($this->journeeCaisse);die();

        $myIntercaisses=$this->getDoctrine()->getRepository(Intercaisses::class)->findMyIntercaisses($this->journeeCaisse);
        return $this->render('intercaisses/ajout.html.twig', [
            'journeeCaisse' => $this->journeeCaisse,
            //'totalR'=>$this->totalR,
            //'totalE'=>$this->totalE,
            'form' => $form->createView(),
            'intercaisse'=>$interCaiss,
            'myIntercaisses'=>$myIntercaisses
        ]);
    }

    /**
     * @Route("/autorise/{id}", name="intercaisses_autoriser", methods="GET|POST|UPDATE")
     */
    public function autoriser(Request $request, InterCaisses $interCaisse): Response
    {
        if ($interCaisse->getStatut() != InterCaisses::INITIE)
        {
            $this->addFlash('error', 'Statut intercaisse non modifiable.');
            return $this->redirectToRoute('intercaisses_ajout');
        }
        //dump($request->request); die();

        if ($request->getMethod()=='UPDATE'){ //Actions sur les intercaisses
            //sécuriser l'opération avec un token
            if ($this->isCsrfTokenValid('update'.$interCaisse->getId(), $request->request->get('_token'))) {
                //bouton "annuler" cliqué
                if ( $request->request->has('annuler')){
                    $interCaisse->setStatut(InterCaisses::ANNULE);
                }
                //bouton "valider" cliqué
                if ( $request->request->has('valider')){
                    $interCaisse=$this->valider($interCaisse);
                }
                $this->getDoctrine()->getManager()->persist($interCaisse);
                $this->getDoctrine()->getManager()->flush();
            }
            return $this->redirectToRoute('intercaisses_ajout');
        }
    }

    /**
     * @Route("/new/{id}", name="inter_caisses_demande", methods="GET|POST")
     */
    public function demander(Request $request, JourneeCaisses $journeeCaisses): Response
    {

        $interCaiss = new InterCaisses();
        $interCaiss->setJourneeCaisseEntrant($journeeCaisses)->setStatut($interCaiss::VALIDATION_AUTO);
        $form = $this->createForm(InterCaissesType::class, $interCaiss);
        $form->handleRequest($request);
        $this->totalInterCaisse($journeeCaisses);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($interCaiss);
            $em->flush();

            return $this->redirectToRoute('inter_caisses_demande',['id'=>$journeeCaisses->getId()]);
        }

        if($request->isXmlHttpRequest()){
            $em=$this->getDoctrine()->getManager();
            if($interCaiss = $request->request->get('intercaisse')) {
                $statut = substr($interCaiss,-2);
                $idIntercaisse = substr($interCaiss,0,-2);
                $interCaisse = $em->getRepository("App:InterCaisses")->find($idIntercaisse);
                $interCaisse->setStatut($statut);
                $em->persist($interCaisse);
            }
            if ($request->request->get('valider')){
                foreach ($journeeCaisses->getIntercaisseEntrants() as $intercaisseEntrant){
                    if($intercaisseEntrant->getStatut() == InterCaisses::ANNULATION_EN_COURS){
                        $this->addFlash('success', "vous avez une intercaisse en cours d'annulation");
                        return $this->redirectToRoute('inter_caisses_index');
                    }
                    elseif($intercaisseEntrant->getStatut() == InterCaisses::VALIDATION_AUTO)
                        $intercaisseEntrant->setStatut(InterCaisses::VALIDE);
                }
                foreach ($journeeCaisses->getIntercaisseSortants() as $intercaisseSortant){
                    if($intercaisseSortant->getStatut() == InterCaisses::ANNULATION_EN_COURS){
                        $this->addFlash('success', "vous avez une intercaisse en cours d'annulation");
                        return $this->redirectToRoute('inter_caisses_demande');
                    }
                    elseif($intercaisseSortant->getStatut() == InterCaisses::VALIDATION_AUTO)
                        $intercaisseSortant->setStatut(InterCaisses::VALIDE);
                }
                $em->persist($journeeCaisses);

                //$journeeCaisses->setMIntercaisse($this->totalR-$this->totalE);
            }

            $em->flush();

        }


        return $this->render('inter_caisses/ajout.html.twig', [
            'journeeCaisse' => $journeeCaisses,
            //'totalR'=>$this->totalR,
            //'totalE'=>$this->totalE,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="inter_caisses_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $interCaiss = new InterCaisses();
        $form = $this->createForm(InterCaissesType::class, $interCaiss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($interCaiss);
            $em->flush();

            return $this->redirectToRoute('inter_caisses_index');
        }

        return $this->render('inter_caisses/new.html.twig', [
            'inter_caiss' => $interCaiss,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}", name="inter_caisses_show", methods="GET")
     */
    public function show(InterCaisses $interCaiss): Response
    {
        return $this->render('inter_caisses/show.html.twig', ['inter_caiss' => $interCaiss]);
    }

    /**
     * @Route("/{id}/edit", name="inter_caisses_edit", methods="GET|POST")
     */
    public function edit(Request $request, InterCaisses $interCaiss): Response
    {
        $form = $this->createForm(InterCaissesType::class, $interCaiss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('inter_caisses_edit', ['id' => $interCaiss->getId()]);
        }

        return $this->render('inter_caisses/edit.html.twig', [
            'inter_caiss' => $interCaiss,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="inter_caisses_delete", methods="DELETE")
     */
    public function delete(Request $request, InterCaisses $interCaiss): Response
    {
        if ($this->isCsrfTokenValid('delete'.$interCaiss->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($interCaiss);
            $em->flush();
        }

        return $this->redirectToRoute('inter_caisses_index');
    }

    /*public function totalInterCaisse(JourneeCaisses $journeeCaisses){
        $em=$this->getDoctrine()->getManager();
        $intercaissesE = $journeeCaisses->getIntercaisseSortants();
        $intercaissesR = $journeeCaisses->getIntercaisseEntrants();
        foreach ($intercaissesR as $intercaiss) if ($intercaiss->getStatut()==InterCaisses::VALIDE)$this->totalR=$this->totalR+$intercaiss->getMIntercaisse();
        foreach ($intercaissesE as $intercaiss) if ($intercaiss->getStatut()==InterCaisses::VALIDE)$this->totalE=$this->totalE+$intercaiss->getMIntercaisse();
        //$journeeCaisses->setIntercaisseEntrant($intercaissesR)->setIntercaisseSortant($intercaissesE);
        //return $journeeCaisses;
    }*/

    private function valider(InterCaisses $interCaisse, $statut=InterCaisses::VALIDE){
        $interCaisse->setStatut($statut);
        $interCaisse->getJourneeCaisseEntrant()->updateM('mIntercaisses', $interCaisse->getMIntercaisse());
        $interCaisse->getJourneeCaisseSortant()->updateM('mIntercaisses', -$interCaisse->getMIntercaisse());
        $genCompta=new GenererCompta($this->getDoctrine()->getManager());
        if (!$genCompta->genComptaIntercaisse($this->utilisateur,$interCaisse->getJourneeCaisseEntrant()->getCaisse(), $interCaisse->getJourneeCaisseSortant()->getCaisse(),$interCaisse->getMIntercaisse(),$interCaisse->getJourneeCaisseEntrant())){
            $this->addFlash('error', $genCompta->getErrMessage());
        };

        return $interCaisse;
    }
}
