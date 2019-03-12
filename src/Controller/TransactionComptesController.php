<?php

namespace App\Controller;

use App\Entity\Caisses;
use App\Entity\Comptes;
use App\Entity\DepotRetrait;
use App\Entity\JourneeCaisses;
use App\Entity\ParamComptables;
use App\Entity\TransactionComptes;
use App\Entity\Transactions;
use App\Entity\Utilisateurs;
use App\Form\DepotRetraitType;
use App\Form\DepotType;
use App\Form\RetraitType;
use App\Form\TransactionComptesType;
use App\Utils\GenererCompta;
use App\Utils\SessionUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/transaction/comptes")
 */
class TransactionComptesController extends Controller
{
    private $journeeCaisse;

    public function __construct(SessionUtilisateur $sessionUtilisateur)
    {
        $this->journeeCaisse=$sessionUtilisateur->getJourneeCaisse();
        if(!$this->journeeCaisse){
            return $this->redirectToRoute('app_login');
        }
    }
    /**
     * @Route("/", name="transaction_comptes_index", methods="GET")
     */
    public function index(): Response
    {
        $transactionComptes = $this->getDoctrine()
            ->getRepository(TransactionComptes::class)
            ->findAll();

        return $this->render('transaction_comptes/index.html.twig', ['transaction_comptes' => $transactionComptes]);
    }

    /**
     * @Route("/depot", name="depot", methods="GET|POST|UPDATE")
     */
    public function depot(Request $request): Response
    {
        $operation=$request->request->get('_operation');
        //$transactionCompte = new TransactionComptes();
        $em = $this->getDoctrine()->getManager();
        $genererCompta=new GenererCompta($em);
        $depot=new DepotRetrait();
        //$journeeCaisses->addDepot($depot);
        $form = $this->createForm(DepotType::class, $depot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $compteClient=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['numCompte'=>$depot->getNumCompte()]);
            //dump($journeeCaisses->getUtilisateur()->getLogin());die();
            $transaction = $genererCompta->genComptaDepot($this->journeeCaisse->getUtilisateur(),$this->journeeCaisse->getCaisse(),$compteClient, $depot->getLibele(), $depot->getMCredit(), $this->journeeCaisse);
            if(!$transaction) {

                //dump($genererCompta->getE()===2);die();
                if($genererCompta->getE()==Transactions::ERR_ZERO)$message =  'Montant égale 0';
                elseif ($genererCompta->getE()==Transactions::ERR_NEGATIF)$message = 'Montant négatif';
                elseif ($genererCompta->getE()===Transactions::ERR_SOLDE_INSUFISANT)$message = ' Solde Insuffisant';
                else $message = 'Deséquilibre';
                $this->addFlash('error', 'erreur : '.$message);
                //dump($genererCompta->getE());die();

            }
            else{
                $this->journeeCaisse->addTransaction($genererCompta->getTransactions()[0]);
                //dump($this->journeeCaisses->getTotalDepot());die();
                //$this->journeeCaisse->setMDepotClient($this->journeeCaisse->getTotalDepot());
                //dump($this->journeeCaisses);die();
                $em->persist($this->journeeCaisse);
                $em->flush();
                if($request->request->has('enregistreretfermer')){
                    return $this->redirectToRoute('journee_caisses_gerer',['id'=>$this->journeeCaisse->getId()]);
                }
                return $this->redirectToRoute('depot',['id'=>$this->journeeCaisse->getId()]);
            }

        }

        if ($request->isXmlHttpRequest()){

            $num=$request->get('num');
            $comptes=$this->getDoctrine()->getManager()->getRepository('App:Comptes')->findOneBy(['numCompte'=>$num]);

            $compte=[
                //['client'=>$comptes?$comptes->getClient()->getPrenom().' '.$comptes->getClient()->getNom():'','intitule'=>$comptes?$comptes->getIntitule():'']
                ['client'=>$comptes?$comptes->getIntitule():'']
            ];

            $data = ["compte"=>$compte];

            return new JsonResponse($data);
        }

        return $this->render('transaction_comptes/depot.html.twig', [
            'journeeCaisse' => $this->journeeCaisse,
            'form' => $form->createView(),
            'operation'=>$operation
        ]);
    }

    /**
     * @Route("/retrait", name="retrait", methods="GET|POST|UPDATE")
     */
    public function retrait(Request $request): Response
    {
        $operation=$request->request->get('_operation');
        //$transactionCompte = new TransactionComptes();
        $em = $this->getDoctrine()->getManager();
        $genererCompta=new GenererCompta($em);
        $retrait=new DepotRetrait();
        $form = $this->createForm(RetraitType::class, $retrait);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //dump($retrait);die();
            $compteClient=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['numCompte'=>$retrait->getNumCompte()]);
            if(!$genererCompta->genComptaRetrait($this->journeeCaisse->getUtilisateur(),$this->journeeCaisse->getCaisse(),$compteClient, $retrait->getLibele(), $retrait->getMDebit(),$this->journeeCaisse))
            {
                //dump($genererCompta->getE()===2);die();
               /* switch ($genererCompta->getE()){
                    case Transactions::ERR_ZERO:$message =  'Opération de montant 0 impossible !!!';break;
                    case Transactions::ERR_NEGATIF:$message =  'Opération de montant négatif non autorisée !!!';break;
                    case Transactions::ERR_SOLDE_INSUFISANT:$message =  'Solde du compte insuffisant pour cette opération !!!';break;
                    case Transactions::ERR_RETRAIT_COMPTE_INTERNE:$message =  'Retrait interdit sur ce compte !!!';break;
                    default : $message='Code erreur N° '.$genererCompta->getE().' Non connu ! ! !';
                }*/
                /*
                if($genererCompta->getE()==Transactions::ERR_ZERO)$message =  'Montant égale 0';
                elseif ($genererCompta->getE()==Transactions::ERR_NEGATIF)$message = 'Montant négatif';
                elseif ($genererCompta->getE()===Transactions::ERR_SOLDE_INSUFISANT)$message = ' Solde Insuffisant';
                else $message = 'Deséquilibre';
                */
                $this->addFlash('error', 'erreur : '.$genererCompta->getErrMessage());
                //dump($genererCompta->getE());die();
            }
            else {
                $this->journeeCaisse->addTransaction($genererCompta->getTransactions()[0]);
                //$this->journeeCaisse->setMRetraitClient($this->journeeCaisse->getTotalRetrait());
                $em->persist($this->journeeCaisse);
                $em->flush();
                if($request->request->has('enregistreretfermer')){
                    return $this->redirectToRoute('journee_caisses_gerer',['id'=>$this->journeeCaisse->getId()]);
                }
                return $this->redirectToRoute('retrait',['id'=>$this->journeeCaisse->getId()]);
            }
        }

        if ($request->isXmlHttpRequest()){

            $num=$request->get('num');
            $comptes=$this->getDoctrine()->getManager()->getRepository('App:Comptes')->findOneBy(['numCompte'=>$num]);

            $compte=[
                ['client'=>$comptes?$comptes->getClient()->getPrenom().' '.$comptes->getClient()->getNom():'','intitule'=>$comptes?$comptes->getIntitule():'','soldeCourant'=>$comptes?$comptes->getSoldeCourant():'']
            ];

            $data = ["compte"=>$compte];

            return new JsonResponse($data);
        }

        return $this->render('transaction_comptes/retrait.html.twig', [
            //'transaction_compte' => $transactionCompte,
            'journeeCaisse' => $this->journeeCaisse,
            'form' => $form->createView(),
            'operation'=>$operation
        ]);
    }
    
    /**
     * @Route("/new", name="transaction_comptes_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $transactionCompte = new TransactionComptes();
        $form = $this->createForm(TransactionComptesType::class, $transactionCompte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($transactionCompte);
            $em->flush();

            return $this->redirectToRoute('transaction_comptes_index');
        }

        return $this->render('transaction_comptes/new.html.twig', [
            'transaction_compte' => $transactionCompte,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="transaction_comptes_show", methods="GET|POST")
     */
    public function show(JourneeCaisses $journeeCaisse): Response
    {
        return $this->render('transaction_comptes/show.html.twig', ['journeeCaisse' => $journeeCaisse]);
    }

    /**
     * @Route("/{id}/edit", name="transaction_comptes_edit", methods="GET|POST")
     */
    public function edit(Request $request, TransactionComptes $transactionCompte): Response
    {
        $form = $this->createForm(TransactionComptesType::class, $transactionCompte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('transaction_comptes_edit', ['id' => $transactionCompte->getId()]);
        }

        return $this->render('transaction_comptes/edit.html.twig', [
            'transaction_compte' => $transactionCompte,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="transaction_comptes_delete", methods="DELETE")
     */
    public function delete(Request $request, TransactionComptes $transactionCompte): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transactionCompte->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($transactionCompte);
            $em->flush();
        }

        return $this->redirectToRoute('transaction_comptes_index');
    }

    public function getTotalDepot(JourneeCaisses $jc){
        $total=0;
        $em=$this->getDoctrine()->getManager();
        $transactions=$em->getRepository('App:Transactions')->findBy(['utilisateur'=>$jc->getUtilisateur()]);
        $depots=$em->getRepository('App:TransactionComptes')->findBy(['transaction'=>$transactions]);
        foreach ($depots as $depot) $total=$total+$depot->getMCredit();
        return $total;
    }

    public function getTotalRetrait(JourneeCaisses $jc){
        $total=0;
        $em=$this->getDoctrine()->getManager();
        $transactions=$em->getRepository('App:Transactions')->findBy(['utilisateur'=>$jc->getUtilisateur()]);
        $retraits=$em->getRepository('App:TransactionComptes')->findBy(['transaction'=>$transactions]);
        foreach ($retraits as $retrait) $total=$total+$retrait->getMDebit();
        return $total;
    }
}
