<?php

namespace App\Controller;

use App\Entity\Caisses;
use App\Entity\CompenseLignes;
use App\Entity\Compenses;
use App\Entity\CriteresDates;
use App\Entity\SystemTransfert;
use App\Entity\TransfertInternationaux;
use App\Form\CompenseCollectionsType;
use App\Form\CompensesType;
use App\Form\CriteresDatesType;
use App\Repository\CompensesRepository;
use App\Utils\GenererCompta;
use App\Utils\SessionUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Route("/compenses")
 */
class CompensesController extends Controller
{
    private  $utilisateur;
    public function __construct(SessionUtilisateur $sessionUtilisateur)
    {
        $this->utilisateur=$sessionUtilisateur->getUtilisateur();
    }

    /**
     * @Route("/", name="compenses_index", methods="GET|POST")
     */
    public function index(Request $request): Response
    {
        $dateDebut=$request->query->get('dateDebut');
        $dateFin=$request->query->get('dateFin');

        $criteresRecherches=new CriteresDates();

        if ($dateDebut) $criteresRecherches->setDateDebut(new \DateTime($dateDebut.' 00:00:00'));
        if ($dateFin) $criteresRecherches->setDateFin(new \DateTime($dateFin.' 23:59:59'));

        $form = $this->createForm(CriteresDatesType::class, $criteresRecherches);
        $form->handleRequest($request);

        $liste = $this->getDoctrine()
            ->getRepository(CompenseLignes::class)
            ->listing($criteresRecherches->getDateDebut(), new \DateTime($criteresRecherches->getDateFin()->format('Y-m-d').' 23:59:59'));

        return $this->render('compenses/index.html.twig', ['compenses' => $liste
        ,'form'=> $form->createView()]);
    }

    /**
     * @Route("/saisie", name="compenses_saisie", methods="GET|POST")
     */
    public function saisir(Request $request): Response
    {
        $banques=$this->getDoctrine()->getRepository(Caisses::class)->findBy(['typeCaisse'=>Caisses::COMPENSE]);

        $compense = new Compenses();

        $dateDebut=$request->request->get('dateDebut')?$request->request->get('dateDebut'):$request->query->get('dateDebut');
        $dateFin=$request->request->get('dateFin')?$request->request->get('dateFin'):$request->query->get('dateFin');
        $banque=$request->request->get('banque')?$request->request->get('banque'):$request->query->get('banque');
        $now=new \DateTime();
        if($dateDebut) $dateDebut=new \DateTime($dateDebut.' 00:00:00');
        else $dateDebut= new \DateTime($now->format('Y-m-d').' 00:00:00');
        if($dateFin) $dateFin=new \DateTime($dateFin.' 23:59:59');
        else $dateFin= new \DateTime($now->format('Y-m-d').' 23:59:59');

        if (!$banque and $banques) $banque=$banques[0]->getId();

        $compense_attendues= $this->getDoctrine()->getRepository(TransfertInternationaux::class)
            ->findCompensations($dateDebut, $dateFin, $banque);

        foreach ($compense_attendues as $compense_attendue){
            $compenseLigne=new CompenseLignes();
            $compenseLigne->setMEnvoiAttendu($compense_attendue['mEnvoi']);
            $compenseLigne->setMReceptionAttendu($compense_attendue['mReception']);
            $systemTransfert=$this->getDoctrine()->getRepository(SystemTransfert::class)->find($compense_attendue['id']);
            $compenseLigne->setSystemTransfert($systemTransfert);
            $compense->addCompenseLigne($compenseLigne);
        }

       $compense->setDateDebut($dateDebut)->setDateFin($dateFin);

        $form = $this->createForm(CompenseCollectionsType::class, $compense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $compense->maintenirTotaux();
            $caisse=$em->getRepository(Caisses::class)->find($banque);
            $compense->setCaisse($caisse);

            //comptabilisation
            $genCompta=new GenererCompta($em);
            $transaction=$genCompta->genComptaCompensation($this->utilisateur,$compense);
            if (!$transaction){
                $this->addFlash('error', $genCompta->getErrMessage());
                return $this->redirectToRoute('compenses_saisie');
            }

            $em->persist($compense);
            $em->flush();

            //marquer les transferts comme compensés
            $em->getRepository(TransfertInternationaux::class)->updateCompense($dateDebut,$dateFin,$compense->getId());

            return $this->redirectToRoute('compenses_show',['id'=>$compense->getId()]);
        }
        return $this->render('compenses/new.html.twig', [
            'compense' => $compense,
            'form' => $form->createView(),
            'dateDebut'=>$dateDebut,
            'dateFin'=>$dateFin,
            'banques'=>$banques,
            'banque_id'=>$banque,
        ]);
    }

    /**
     * @Route("/{id}", name="compenses_show", methods="GET")
     */
    public function show(Compenses $compense): Response
    {
        return $this->render('compenses/show.html.twig', ['compense' => $compense]);
    }

    /**
     * @Route("/{id}/modifier", name="compenses_edit", methods="GET|POST")
     */
    public function modifier(Request $request, Compenses $compense): Response
    {
        $form = $this->createForm(CompenseCollectionsType::class, $compense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $compense->maintenirTotaux();
            $em=$this->getDoctrine()->getManager();

            //mise à jour compta
            $genCompta=new GenererCompta($em);
            $transaction=$genCompta->modifComptaCompensation($this->utilisateur,$compense);
            if (!$transaction){
                $this->addFlash('error', $genCompta->getErrMessage());
                return $this->redirectToRoute('compenses_saisie');
            }
            $em->flush();

            return $this->redirectToRoute('compenses_show',['id'=>$compense->getId()]);
        }
        $banques=$this->getDoctrine()->getRepository(Caisses::class)->findBy(['typeCaisse'=>Caisses::COMPENSE]);

        return $this->render('compenses/edit.html.twig', [
            'compense' => $compense,
            'form' => $form->createView(),
            'dateDebut'=>$compense->getDateDebut(),
            'dateFin'=>$compense->getDateFin(),
            'banques'=>$banques,
            'banque_id'=>$compense->getCaisse()->getId(),
        ]);
    }

    /**
     * @Route("/{id}", name="compenses_delete", methods="DELETE")
     */
    public function delete(Request $request, Compenses $compense): Response
    {
        if ($this->isCsrfTokenValid('delete'.$compense->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($compense);
            $em->flush();
        }

        return $this->redirectToRoute('compenses_index');
    }
}
