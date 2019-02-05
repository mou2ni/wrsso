<?php

namespace App\Controller;

use App\Entity\RecetteDepenses;
use App\Form\RecetteDepenseJourneesType;
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
     * @Route("/saisie", name="recette_depenses_saisie", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $recetteDepense = new RecetteDepenses();
        $recetteDepense->setUtilisateur($this->utilisateur)->setJourneeCaisse($this->journeeCaisse)->setStatut(RecetteDepenses::STAT_INITIAL);
        $form = $this->createForm(RecetteDepensesType::class, $recetteDepense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if (!$recetteDepense->comptabiliser($em,$this,$this->journeeCaisse)){
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
     * @Route("/saisiegroupee", name="recette_depenses_saisie_groupee", methods="GET|POST")
     */
    public function saisieGroupe(Request $request): Response
    {
        $form = $this->createForm(RecetteDepenseJourneesType::class, $this->journeeCaisse);
        $form->handleRequest($request);

        //dump($request);die();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            //dump($this->journeeCaisse);die();
            foreach ($this->journeeCaisse->getRecetteDepenses() as $recetteDepense) {

                if ($recetteDepense->getStatut()==RecetteDepenses::STAT_INITIAL or $recetteDepense->getStatut()==null){
                    $recetteDepense->setEstComptant(true);
                    $genCompta=$recetteDepense->comptabiliser($em,$this,$this->journeeCaisse);
                    if (!$genCompta) {
                        return $this->render('recette_depenses/recette_depense_journee.html.twig', ['journeeCaisse' => $this->journeeCaisse,'form' => $form->createView(), ]);
                    }
                    //$recetteDepense->setTransaction($genCompta->getTransactions()[0]);
                    //$recetteDepense->setStatut(RecetteDepenses::STAT_COMPTA);
                    //$em->persist($recetteDepense);
                }
            }
            $this->journeeCaisse->maintenirRecetteDepenses();
            $em->persist($this->journeeCaisse);
            $em->flush();
            $this->addFlash('success','Depenses et recettes bien enregistré');

            if($request->request->has('save_and_close')){
                return $this->redirectToRoute('compta_saisie_cmd');
            }

            return $this->redirectToRoute('recette_depenses_saisie_groupee');

            /*if($request->request->has('save_and_close')){
                return $this->redirectToRoute('journee_caisses_gerer');
            }
            if($request->request->has('save_and_close')){
                return $this->redirectToRoute('recette_depenses_saisie_groupee');
            }*/

        }

        return $this->render('recette_depenses/recette_depense_journee.html.twig', [
            //'recette_depense' => $recetteDepense,
            'journeeCaisse' => $this->journeeCaisse,
            'form' => $form->createView(),
        ]);
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
