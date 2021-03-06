<?php

namespace App\Controller;

use App\Entity\Collaborateurs;
use App\Entity\Comptes;
use App\Entity\LigneSalaires;
use App\Entity\ParamComptables;
use App\Entity\Salaires;
use App\Entity\TransactionComptes;
use App\Entity\Transactions;
use App\Form\SalairesType;
use App\Repository\SalairesRepository;
use App\Utils\GenererCompta;
use App\Utils\SessionUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/salaires")
 */
class SalairesController extends Controller
{
    private $journeeCaisse;
    private $utilisateur;
    private $caisse;

    public function __construct(SessionUtilisateur $sessionUtilisateur)
    {
        $this->utilisateur=$sessionUtilisateur->getUtilisateur();
        //dernière caisse ouverte par l'utilisateur ou null si inexistant
        $this->caisse=$sessionUtilisateur->getLastCaisse();
        //dernière journée de la caisse ou null si inexistant
        $this->journeeCaisse=$sessionUtilisateur->getJourneeCaisse();
    }
    /**
     * @Route("/", name="salaires_index", methods="GET")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function index(SalairesRepository $salairesRepository): Response
    {
        return $this->render('salaires/index.html.twig', ['salaires' => $salairesRepository->findAll()]);
    }

    /**
     * @Route("/{id}/ecriturecomptables", name="salaires_ecriture_comptables", methods="GET|POST")
     */
    public function detatilTransaction(Request $request, Salaires $salaire): Response
    {
        return $this->render('salaires/detail_transactions.html.twig', ['salaire' =>$salaire]);
    }

    /**
     * @Route("/positionnement", name="salaires_positionnement", methods="GET|POST")
     */
    public function positionner(Request $request,\Swift_Mailer $mailer): Response
    {
        //$em=$this->getDoctrine()->getManager();
        //$salaire=$em->getRepository(Salaires::class)->findOneBy(['periodeSalaire'=>$periodeSalaire]);

        if (!$this->journeeCaisse){
            $this->addFlash('error', 'L\'Ouverture d\'une journée caisse est obligatoire pour continuer');
            return $this->redirectToRoute('journee_caisses_gerer');
        }

        
        $collaborateurs=$this->getDoctrine()->getRepository(Collaborateurs::class)->findBy(['statut'=>Collaborateurs::STAT_SALARIE]);

        $salaire = new Salaires();
        //mettre les données par defaut si premier chargement
        if ($request->request->get('operation')!='positionner'){
            //dump($collaborateurs);
            $salaire->fillLigneSalaireFromCollaborateurs($collaborateurs);
        }
        //$salaire->setDateSalaire(new \DateTime());
        $form = $this->createForm(SalairesType::class, $salaire);
        $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           $em = $this->getDoctrine()->getManager();

           $salaire->maintenirTotaux();

           //$verif_salaire=$em->getRepository(Salaires::class)->findOneBy('periode')

            $genCompta=new GenererCompta($em);
           $transaction = $genCompta->genComptaSalaire($this->utilisateur, $salaire, $this->journeeCaisse);
           if (!$transaction) {
               $this->addFlash('error', $genCompta->getErrMessage());
               if ($genCompta->getE() == Transactions::ERR_DESEQUILIBRE) {
                   return $this->render('transactions/erreur_desequilibre.html.twig', ['transaction' => $genCompta->getTransactions()[0]]);
               }
               return $this->redirectToRoute('salaires_positionnement');
           }

           $em->persist($salaire);
            $em->flush();

           //envoi mail si non compte interne
           foreach ($genCompta->getTransactions() as $transaction){
               foreach ($transaction->getTransactionComptes() as $transactionCompte)
               {
                   $compte=$transactionCompte->getCompte();
                   $adresseMailTo=$compte->getClient()->getEmail();
                   if ($compte->getTypeCompte() != Comptes::INTERNE and $adresseMailTo) {
                       $message_object = 'WARISSO - Confirmation de mouvement';
                       $message = (new \Swift_Message($message_object))
                           ->setFrom('warisso-confirm@yesbo.bf')
                           ->setTo($adresseMailTo)
                           ->setBody($this->renderView('transaction_comptes/confirmation_mail.html.twig',
                               ['transactionCompte' => $transactionCompte]
                           ), 'text/html');

                       $mailer->send($message);
                   }
               }
           }
            return $this->redirectToRoute('salaires_ecriture_comptables',['id'=>$salaire->getId()]);
        }
        return $this->render('salaires/positionnement.html.twig', [
            'salaire' => $salaire,
            'form' => $form->createView(),
        ]);
    }


    /*
     * @Route("/new", name="salaires_new", methods="GET|POST")

    public function new(Request $request): Response
    {
        $salaire = new Salaires();
        $form = $this->createForm(SalairesType::class, $salaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($salaire);
            $em->flush();

            return $this->redirectToRoute('salaires_index');
        }

        return $this->render('salaires/new.html.twig', [
            'salaire' => $salaire,
            'form' => $form->createView(),
        ]);
    }*/

    /**
     * @Route("/{id}/maintenir", name="salaires_maintenir", methods="GET")
     */
    public function maintenir(Salaires $salaire): Response
    {
        $salaire->maintenirTotaux();
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('salaires_index');
    }

    /**
     * @Route("/{id}", name="salaires_show", methods="GET")
     */
    public function show(Salaires $salaire): Response
    {
        $ligne_salaires=$this->getDoctrine()->getRepository(LigneSalaires::class)->findListingLigneJoinCollabo($salaire);

        return $this->render('salaires/show.html.twig', ['salaire' => $salaire,
        'ligne_salaires'=>$ligne_salaires,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="salaires_edit", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function edit(Request $request, Salaires $salaire): Response
    {
        $form = $this->createForm(SalairesType::class, $salaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('salaires_index', ['id' => $salaire->getId()]);
        }

        return $this->render('salaires/edit.html.twig', [
            'salaire' => $salaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="salaires_delete", methods="DELETE")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function delete(Request $request, Salaires $salaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$salaire->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($salaire);
            $em->flush();
        }

        return $this->redirectToRoute('salaires_index');
    }
}
