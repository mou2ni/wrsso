<?php

namespace App\Controller;

use App\Entity\Caisses;
use App\Entity\Comptes;
use App\Entity\DepotRetrait;
use App\Entity\JourneeCaisses;
use App\Entity\ParamComptables;
use App\Entity\TransactionComptes;
use App\Entity\Utilisateurs;
use App\Form\DepotRetraitType;
use App\Form\TransactionComptesType;
use App\Utils\GenererCompta;
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
     * @Route("/depot/{id}", name="depot", methods="GET|POST")
     */
    public function depot(Request $request, JourneeCaisses $journeeCaisses): Response
    {
        //$transactionCompte = new TransactionComptes();
        $em = $this->getDoctrine()->getManager();
        $genererCompta=new GenererCompta($em);
                $depot=new DepotRetrait();
                //$journeeCaisses->addDepot($depot);
        $form = $this->createForm(DepotRetraitType::class, $depot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $compteClient=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['numCompte'=>$depot->getNumCompte()]);

            //dump($journeeCaisses->getUtilisateur()->getLogin());die();
            $transaction = $genererCompta->genComptaDepot($journeeCaisses->getUtilisateur(),$journeeCaisses->getCaisse(),$compteClient, $depot->getLibele(), $depot->getMCredit());
            if(!$transaction) return $this->render( 'comptMainTest.html.twig',['transactions'=>[$genererCompta->getTransactions()]]);
            $journeeCaisses->addTransaction($genererCompta->getTransactions()[0]);
            $journeeCaisses->setMDepotClient($this->getTotalDepot($journeeCaisses));
            $em->persist($journeeCaisses);
            $em->flush();

            return $this->redirectToRoute('depot',['id'=>$journeeCaisses->getId()]);
        }

        if ($request->isXmlHttpRequest()){

            $num=$request->get('num');
            $comptes=$this->getDoctrine()->getManager()->getRepository('App:Comptes')->findOneBy(['numCompte'=>$num]);

            $compte=[
                ['client'=>$comptes?$comptes->getClient()->getPrenom().' '.$comptes->getClient()->getNom():'','intitule'=>$comptes?$comptes->getIntitule():'']
            ];

            $data = ["compte"=>$compte];

            return new JsonResponse($data);
        }

        return $this->render('transaction_comptes/depot.html.twig', [
            'journeeCaisse' => $journeeCaisses,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/retrait/{id}", name="retrait", methods="GET|POST")
     */
    public function retrait(Request $request, JourneeCaisses $journeeCaisse): Response
    {
        //$transactionCompte = new TransactionComptes();
        $em = $this->getDoctrine()->getManager();
        $genererCompta=new GenererCompta($em);
        $retrait=new DepotRetrait();
        $form = $this->createForm(DepotRetraitType::class, $retrait);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $compteClient=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['numCompte'=>$retrait->getNumCompte()]);
            /*dump($journeeCaisses->getUtilisateur());
            dump($journeeCaisses->getIdCaisse());
            dump($compteClient);
            dump($retrait->getLibele());
            dump($retrait->getMDebit());
            die();*/
            if(!$genererCompta->genComptaRetrait($journeeCaisse->getUtilisateur(),$journeeCaisse->getCaisse(),$compteClient, $retrait->getLibele(), $retrait->getMDebit())) return $this->render( 'comptMainTest.html.twig',['transactions'=>[$genererCompta->getTransactions()]]);
            $journeeCaisse->addTransaction($genererCompta->getTransactions()[0]);
            $journeeCaisse->setMRetraitClient($this->getTotalRetrait($journeeCaisse));
            $em->persist($journeeCaisse);
            $em->flush();

            return $this->redirectToRoute('retrait',['id'=>$journeeCaisse->getId()]);
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
            'journeeCaisse' => $journeeCaisse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/retrait", name="retrait", methods="GET|POST")

    public function retrait(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $genererCompta=new GenererCompta($em);
        $journeeCaisses= $em->getRepository('App:JourneeCaisses')->find(1);
        $utilisateur=$this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['login'=>'asanou']);

        $retrait=new DepotRetrait();
        $form = $this->createForm(DepotRetraitType::class, $retrait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $compteClient=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['numCompte'=>$retrait->getNumCompte()]);
            dump($journeeCaisses->getUtilisateur());die();
            if(!$genererCompta->genComptaRetrait($utilisateur,$journeeCaisses->getIdCaisse(),$compteClient, "RETRAIT TEST", 40000)) return $this->render( 'comptMainTest.html.twig',['transactions'=>[$genererCompta->getTransactions()]]);
            //if(!$genererCompta->genComptaRetrait($journeeCaisses->getIdUtilisateur(),$journeeCaisses->getIdCaisse(),$compteClient, $retrait->getLibele(), $retrait->getMDebit())) return $this->render( 'comptMainTest.html.twig',['transactions'=>[$genererCompta->getTransactions()]]);

            //if(!$genererCompta->genComptaRetrait($journeeCaisses->getIdUtilisateur(),$journeeCaisses->getIdCaisse(),$compteClient, $retrait->getLibele(), $retrait->getMDebit())) return $this->render( 'comptMainTest.html.twig',['transactions'=>[$genererCompta->getTransactions()]]);

            $journeeCaisses->setMDepotClient($this->getTotalDepot($journeeCaisses));
            $em->persist($journeeCaisses);
            $em->flush();

            return $this->redirectToRoute('transaction_comptes_index');
        }

        if ($request->isXmlHttpRequest()){

            $num=$request->get('num');
            $comptes=$this->getDoctrine()->getManager()->getRepository('App:Comptes')->findOneBy(['numCompte'=>$num]);

            $compte=[
                ['client'=>$comptes?$comptes->getClient()->getPrenom().' '.$comptes->getClient()->getNom():'','intitule'=>$comptes?$comptes->getIntitule():'']
            ];

            $data = ["compte"=>$compte];

            return new JsonResponse($data);
        }

        return $this->render('transaction_comptes/retrait.html.twig', [
            //'transaction_compte' => $transactionCompte,
            'form' => $form->createView(),
        ]);
    }
*/
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
     * @Route("/{id}", name="transaction_comptes_show", methods="GET")
     */
    public function show(TransactionComptes $transactionCompte): Response
    {
        return $this->render('transaction_comptes/show.html.twig', ['transaction_compte' => $transactionCompte]);
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
