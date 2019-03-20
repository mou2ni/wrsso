<?php

namespace App\Controller;

use App\Entity\Caisses;
use App\Entity\TransactionComptes;
use App\Entity\Transactions;
use App\Form\TransactionsType;
use App\Utils\SessionUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/transactions")
 */
class TransactionsController extends Controller
{
    private $utilisateur;

    public function __construct(SessionUtilisateur $sessionUtilisateur)
    {
        $this->utilisateur=$sessionUtilisateur->getUtilisateur();
    }

    /**
     * @Route("/", name="transactions_index", methods="GET")
     */
    public function index(Request $request): Response
    {
        $limit=20;
        $_page=$request->query->get('_page');
        $offset = ($_page)?($_page-1)*$limit:0;
        $liste = $this->getDoctrine()
            ->getRepository(Transactions::class)
            ->listePaginee($offset,$limit);
        $pages = round(count($liste)/$limit);

        return $this->render('transactions/index.html.twig', ['transactions' => $liste, 'pages'=>$pages]);
    }

    /**
     * @Route("/ajout", name="transactions_ajout", methods="GET|POST")
     */
    public function ajout(Request $request): Response
    {
        $transaction = new Transactions();
        $form = $this->createForm(TransactionsType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $transaction->setUtilisateur($this->utilisateur);
            $em->persist($transaction);
            $em->flush();

            return $this->redirectToRoute('transactions_ajout');
        }
        
        $transactions=$this->getDoctrine()->getRepository(Transactions::class)->liste(0,5);
        return $this->render('transactions/new.html.twig', [
            'transaction' => $transaction,
            'transactions'=>$transactions,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/detail", name="transactions_show", methods="GET")
     */
    public function show(Transactions $transaction): Response
    {
        //$transaction=$this->getDoctrine()->getRepository(TransactionComptes::class)->getEcriture($id);
        //dump($transaction);die();
        return $this->render('transactions/show.html.twig', ['transaction' => $transaction]);
    }

    /**
     * @Route("/{id}/modifier", name="transactions_edit", methods="GET|POST")
     */
    public function edit(Request $request, Transactions $transaction): Response
    {
        $journeeCaisse=$transaction->getJourneeCaisse();
        if($journeeCaisse){
            if( $journeeCaisse->getCaisse()->getTypeCaisse()==Caisses::GUICHET){
                $this->addFlash('error','Modification impossible des écritures comptables de guichet');
                return $this->redirectToRoute('transactions_ajout');
            }
        }
        $form = $this->createForm(TransactionsType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $transaction->maintenir();
            if ($transaction->isDesequilibre()){
                $this->addFlash('error','Ecriture déséquilibrée ! ! !');
                return $this->redirectToRoute('transactions_edit',['id'=>$transaction->getId()]);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('transactions_index');
        }

        $transactions=$this->getDoctrine()->getRepository(Transactions::class)->liste(0,5);
        return $this->render('transactions/edit.html.twig', [
            'transaction' => $transaction,
            'transactions'=>$transactions,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="transactions_delete", methods="DELETE")
     */
    public function delete(Request $request, Transactions $transaction): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transaction->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($transaction);
            $em->flush();
        }

        return $this->redirectToRoute('transactions_index');
    }
}
