<?php

namespace App\Controller;

use App\Entity\RecetteDepenses;
use App\Form\RecetteDepenseJourneesType;
use App\Form\RecetteDepensesComptantsType;
use App\Form\RecetteDepensesType;
use App\Repository\RecetteDepensesRepository;
use App\Utils\GenererCompta;
use App\Utils\SessionUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/recettedepenses")
 */
class RecetteDepensesController extends Controller
{
    public function __construct(SessionUtilisateur $sessionUtilisateur)
    {
        $this->utilisateur = $sessionUtilisateur->getUtilisateur();
        //dernière caisse ouverte par l'utilisateur ou null si inexistant
        $this->caisse = $sessionUtilisateur->getLastCaisse();
        //dernière journée de la caisse ou null si inexistant
        $this->journeeCaisse = $sessionUtilisateur->getJourneeCaisse();
    }
    /**
     * @Route("/", name="recette_depenses_index", methods="GET")
     */
    public function index(RecetteDepensesRepository $recetteDepensesRepository): Response
    {
        return $this->render('recette_depenses/index.html.twig', ['recette_depenses' => $recetteDepensesRepository->findAll()]);
    }

    /**
     * @Route("/saisieComptant", name="recette_depenses_comptant", methods="GET|POST")
     */
    public function ajoutComptant(Request $request): Response
    {
        return $this->ajout($request);
    }

    /**
     * @Route("/saisieAterme", name="recette_depenses_aterme", methods="GET|POST")
     */
    public function ajoutAterme(Request $request): Response
    {
        return $this->ajout($request,false);
    }

    private function ajout(Request $request, $estComptant=true): Response
    {
        $recetteDepense = new RecetteDepenses();
        $recetteDepense->setUtilisateur($this->utilisateur)->setJourneeCaisse($this->journeeCaisse)->setStatut(RecetteDepenses::STAT_INITIAL);
        $form = ($estComptant)?$this->createForm(RecetteDepensesComptantsType::class, $recetteDepense)
        :$this->createForm(RecetteDepensesType::class, $recetteDepense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $genCompta=new GenererCompta($em);

            if (!$recetteDepense->comptabiliserNouveau($genCompta,$this->journeeCaisse)){
                return $this->redirectToRoute('recette_depenses_saisie');
            }
            //$em->persist($recetteDepense);
            $em->persist($this->journeeCaisse);
            $em->flush();

            if($request->request->has('save_and_close')){
                return $this->redirectToRoute('journee_caisses_gerer');
            }
            if($request->request->has('save_and_close')){
                return $this->redirectToRoute('recette_depenses_saisie');
            }

        }

        return $this->render('recette_depenses/new.html.twig', [
            'recette_depense' => $recetteDepense,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/saisiegroupeecomptant", name="recette_depenses_comptant_groupee", methods="GET|POST")
     */
    public function saisieComptantGroupee(Request $request):Response
    {
        $form = $this->createForm(RecetteDepenseJourneesType::class, $this->journeeCaisse);
        $form->handleRequest($request);
        //dump($request);die();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //dump($this->journeeCaisse);die();
            foreach ($this->journeeCaisse->getRecetteDepenses() as $recetteDepense) {
                $genCompta=new GenererCompta($em);
                $recetteDepense->setCompteTier($this->caisse->getCompteOperation());
                if ($recetteDepense->getStatut()==RecetteDepenses::STAT_INITIAL or $recetteDepense->getStatut()==null) {
                    $recetteDepense->setEstComptant(true);
                    $ok = $recetteDepense->comptabiliserNouveau($genCompta, $this->journeeCaisse);
                    //$ok=$genCompta->genComptaRecetteDepenseComptant($this->utilisateur,$this->caisse,$recetteDepense,$this->journeeCaisse);
                    if (!$ok) {
                        $this->addFlash('error', $genCompta->getErrMessage());
                        return $this->render('recette_depenses/recette_depense_journee.html.twig', ['journeeCaisse' => $this->journeeCaisse, 'form' => $form->createView(),]);
                    }
                }
                //$recetteDepense->setTransaction($genCompta->getTransactions()[0]);
                //$recetteDepense->setStatut(RecetteDepenses::STAT_COMPTA);
                //$em->persist($recetteDepense);
            }
            $this->journeeCaisse->maintenirRecetteDepenses();
            $em->persist($this->journeeCaisse);
            $em->flush();
            $this->addFlash('success','Depenses et recettes bien enregistré');

            if($request->request->has('save_and_close')){
                return $this->redirectToRoute('compta_saisie_cmd');
            }
            return $this->redirectToRoute('recette_depenses_comptant_groupee');
        }

        return $this->render('recette_depenses/recette_depense_journee.html.twig', [
            //'recette_depense' => $recetteDepense,
            'journeeCaisse' => $this->journeeCaisse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/saisiegroupeeaterme", name="recette_depenses_aterme_groupee", methods="GET|POST")
     */
    public function saisieAtermeGroupee(Request $request):Response
    {

    }


    /**
     * @Route("/{id}", name="recette_depenses_details", methods="GET")
     */
    public function show(RecetteDepenses $recetteDepense): Response
    {
        return $this->render('recette_depenses/show.html.twig', ['recette_depense' => $recetteDepense]);
    }

    /**
     * @Route("/{id}/modif", name="recette_depenses_modif", methods="GET|POST")
     */
    public function edit(Request $request, RecetteDepenses $recetteDepense): Response
    {
        $form = $this->createForm(RecetteDepensesType::class, $recetteDepense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recette_depenses_index', ['id' => $recetteDepense->getId()]);
        }

        return $this->render('recette_depenses/edit.html.twig', [
            'recette_depense' => $recetteDepense,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recette_depenses_suppr", methods="DELETE")
     */
    public function delete(Request $request, RecetteDepenses $recetteDepense): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recetteDepense->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($recetteDepense);
            $em->flush();
        }

        return $this->redirectToRoute('recette_depenses_index');
    }
}
