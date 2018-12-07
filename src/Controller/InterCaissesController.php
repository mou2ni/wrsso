<?php

namespace App\Controller;

use App\Entity\InterCaisses;
use App\Entity\JourneeCaisses;
use App\Entity\ParamComptables;
use App\Form\InterCaissesJourneeType;
use App\Form\InterCaissesType;
use App\Form\JourneeCaissesType;
use App\Utils\GenererCompta;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @Route("/ajout/{id}", name="inter_caisses_ajout", methods="GET|POST|UPDATE")
     */
    public function ajout(Request $request, JourneeCaisses $journeeCaisses): Response
    {

        $operation=$request->request->get('_operation');
        $interCaiss = new InterCaisses();
        $interCaiss->setJourneeCaisseEntrant($journeeCaisses)->setStatut($interCaiss::INITIE);
        $form = $this->createForm(InterCaissesType::class, $interCaiss);
        $form->handleRequest($request);
        //$this->totalInterCaisse($journeeCaisses);
        //$journeeCaisses->setMIntercaisses($this->totalE-$this->totalR);


        if ($form->isSubmitted() && $form->isValid()) {
            $operation=$request->request->get('_operation');
            $em = $this->getDoctrine()->getManager();
            $em->persist($interCaiss);
            $em->flush();

            if($request->request->has('enregistreretfermer')){
                return $this->redirectToRoute('journee_caisses_gerer');
            }
            return $this->redirectToRoute('inter_caisses_ajout', ['id'=>$journeeCaisses->getId()]);

        }

        if($request->isXmlHttpRequest()){
            $em=$this->getDoctrine()->getManager();
            if($interCaiss = $request->request->get('intercaisse')) {
                $statut = $interCaiss[1];// . $interCaiss[2];
                $idIntercaisse = $interCaiss[0];
                $statut = substr($interCaiss,-1);
                $idIntercaisse = substr($interCaiss,0,-1);
                $interCaisse = $em->getRepository("App:InterCaisses")->find($idIntercaisse);
                if ($statut=='V')$interCaisse->valider();
                else $interCaisse->setStatut(InterCaisses::ANNULE);
                //$interCaisse->setStatut($statut);
                $em->persist($interCaisse);
                //$journeeCaisses->setMIntercaisses($this->totalR-$this->totalE);
            }
            if ($request->request->get('valider')){
                //$journeeCaisses->setMIntercaisses($this->totalR-$this->totalE);
                foreach ($journeeCaisses->getIntercaisseSortants() as $intercaisseSortant ){
                    if ($intercaisseSortant->getStatut()== InterCaisses::INITIE){
                        $intercaisseSortant->valider();
                    }
                }

            }

            $em->flush();

        }


        return $this->render('inter_caisses/ajout.html.twig', [
            'journeeCaisse' => $journeeCaisses,
            'totalR'=>$this->totalR,
            'totalE'=>$this->totalE,
            'form' => $form->createView(),
            'operation'=>$operation
        ]);
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
            'totalR'=>$this->totalR,
            'totalE'=>$this->totalE,
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

    public function totalInterCaisse(JourneeCaisses $journeeCaisses){
        $em=$this->getDoctrine()->getManager();
        $intercaissesE = $journeeCaisses->getIntercaisseSortants();
        $intercaissesR = $journeeCaisses->getIntercaisseEntrants();
        foreach ($intercaissesR as $intercaiss) if ($intercaiss->getStatut()==InterCaisses::VALIDE)$this->totalR=$this->totalR+$intercaiss->getMIntercaisse();
        foreach ($intercaissesE as $intercaiss) if ($intercaiss->getStatut()==InterCaisses::VALIDE)$this->totalE=$this->totalE+$intercaiss->getMIntercaisse();
        //$journeeCaisses->setIntercaisseEntrant($intercaissesR)->setIntercaisseSortant($intercaissesE);
        //return $journeeCaisses;
    }
}
