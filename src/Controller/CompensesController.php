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
    /**
     * @Route("/", name="compenses_index", methods="GET")
     */
    public function index(Request $request): Response
    {
        $limit=20;
        $_page=$request->query->get('_page');
        $offset = ($_page)?($_page-1)*$limit:0;
        $liste = $this->getDoctrine()
            ->getRepository(Compenses::class)
            ->liste($offset,$limit);
        $pages = round(count($liste)/$limit);

        return $this->render('compenses/index.html.twig', ['compenses' => $liste, 'pages'=>$pages, 'path'=>'compenses_index']);
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
            $compense->maintenirTotaux();
            $caisse=$this->getDoctrine()->getRepository(Caisses::class)->find($banque);
            $compense->setCaisse($caisse);

            //if($request->request->get('save_and_close') or $request->request->get('save_and_new') ) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($compense);
                $em->flush();
                return $this->redirectToRoute('compenses_index');
            //}


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
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('compenses_show',['id'=>$compense->getId()]);
        }

        return $this->render('compenses/edit.html.twig', [
            'compense' => $compense,
            'form' => $form->createView(),
            'dateDebut'=>$compense->getDateDebut(),
            'dateFin'=>$compense->getDateFin(),
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
