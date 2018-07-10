<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 27/06/2018
 * Time: 13:37
 */

namespace App\Controller;

use App\Entity\Caisses;
use App\Entity\Comptes;
use App\Entity\Transactions;
use App\Entity\TransactionComptes;
//use App\Entity\ParamComptables;
use App\Entity\Utilisateurs;

//use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/compta")
 */
class GenererEcritureComptaController extends Controller
{
    /*
     * @var
     *
     */
    //private $em;

    /**
     * @var Transactions
     * Les écritures comptables
     */
    //private $_trans;

    /**
     * @var
     *
     * Le plan des comptes
     */
    //private $_pc; //Plan comptable

    public function __construct()
    {
        //$this->_trans = new Transactions();
        //$this->_em = EntityManager();

    }

    /**
     * @param Transactions $transaction
     * @param Comptes $compte
     * @param $montant
     * @param bool $estCredit
     * @return TransactionComptes
     */
    private function fillTransactionCompte(Comptes $compte, $montant, $estDebit=true){
        $mouvement=new TransactionComptes();
        //$mouvement->setTransaction($transaction);
        $mouvement->setCompte($compte);
        $mouvement->setNumCompte($compte->getNumCompte());
        ($estDebit)?$mouvement->setMDebit($montant):$mouvement->setMCredit($montant);

        return $mouvement;
    }

    private function initTransaction(Utilisateurs $utilisateur, $libelle, $montant)
    {
        $transaction=new Transactions();
        //montant=0 alors ressortir avec ERR_ZERO
        ($montant==0 )?$transaction->setE($transaction::ERR_ZERO):$transaction->setUtilisateur($utilisateur)->setLibelle($libelle)->setDateTransaction( new \DateTime());
        return $transaction;
    }

    private function debiterCrediter($transaction, Comptes $compteDebit, Comptes $compteCredit, $montant)
    {
        //vérification de la non nullité des comptes transmis
        //if ($compteDebit==null or $compteCredit==null) return new Transactions();

        $montant = abs($montant);
        //ajout de ligne d'écriture debit
        $transaction->addTransactionComptes($this->fillTransactionCompte($compteDebit, $montant, true));

        //ajout de ligne d'écriture credit
        $transaction->addTransactionComptes($this->fillTransactionCompte($compteCredit, $montant, false));

        $em=$this->getDoctrine()->getManager();
        $em->persist($transaction);
        $em->flush();
        return $transaction;
    }

    private function initDepotRetrait(Utilisateurs $utilisateur, $libelle, $montant)
    {
        //initiation et controle des conditions de validité de l'appel
        $transaction=$this->initTransaction($utilisateur,$libelle,$montant);
        if ($transaction->getE()) return $transaction ;
        //Depot avec montant négatif interdit
        if($montant<0)
        {
            $transaction->setE($transaction::ERR_NEGATIF);
            return $transaction;
        }else {
            return $transaction;
        }

    }

    /**
     * @param Utilisateurs $utilisateur
     * @param Comptes $compteOperationCaisse
     * @param Comptes $compteEcartUtilisateur
     * @param int $montant
     * @return Transactions
     */
    public function genComptaEcartOuv(Utilisateurs $utilisateur, Comptes $compteOperationCaisse, $montant)
    {
        //initiation et controle des conditions de validité de l'appel
        $transaction=$this->initTransaction($utilisateur,"Ecart d'ouverture",$montant);

        if ($transaction->getE()) return $transaction ;

        $compteEcartUtilisateur=$utilisateur->getIdCompteEcart();

        //ajout des lignes de debit et crédit : comptes en fonction du signe de l'écart
        ($montant>0)?$transaction=$this->debiterCrediter($transaction,$compteEcartUtilisateur,$compteOperationCaisse,$montant)
                     :$transaction=$this->debiterCrediter($transaction,$compteOperationCaisse,$compteEcartUtilisateur,$montant);

        return $transaction;
    }

    /**
     * @param Utilisateurs $utilisateur
     * @param Comptes $compteOperationCaisse
     * @param Comptes $compteClient
     * @param $libelle
     * @param $montant
     * @return Transactions
     */
    public function genComptaDepot(Utilisateurs $utilisateur, Comptes $compteOperationCaisse, Comptes $compteClient, $libelle, $montant)
    {
        $transaction=$this->initDepotRetrait($utilisateur, $libelle, $montant);

        if ($transaction->getE()) {
            return $transaction;
        }
        else {
            return $this->debiterCrediter($transaction, $compteOperationCaisse, $compteClient, $montant);
        }

    }

    /**
     * @param Utilisateurs $utilisateur
     * @param Comptes $compteOperationCaisse
     * @param Comptes $compteClient
     * @param $libelle
     * @param $montant
     * @return Transactions
     */
    public function genComptaRetrait(Utilisateurs $utilisateur, Comptes $compteOperationCaisse, Comptes $compteClient, $libelle, $montant)
    {

        $transaction=$this->initDepotRetrait($utilisateur, $libelle, $montant);

        if ($transaction->getE()) {
            return $transaction;
        }
        elseif($compteClient->getSoldeCourant()< $montant) {
            $transaction->setE($transaction::ERR_SOLDE_INSUFISANT);
            return $transaction;
        }else {
            //ajout des lignes d'écritures debit et crédit
            return $this->debiterCrediter($transaction, $compteClient, $compteOperationCaisse, $montant);
        }

    }


    /**
     * @param Utilisateurs $utilisateur
     * @param Comptes $compteOperationCaisse
     * @param Comptes $compteCharge
     * @param $libelle
     * @param $montant
     * @return Transactions
     */
    public function genComptaDepenses(Utilisateurs $utilisateur, Comptes $compteOperationCaisse, Comptes $compteCharge, $libelle, $montant)
    {
        $transaction=$this->initTransaction($utilisateur,$libelle,$montant);

        if ($transaction->getE()) return $transaction ;

        return $this->debiterCrediter($transaction, $compteCharge, $compteOperationCaisse, $montant);
    }

    /**
     * @param Utilisateurs $utilisateur
     * @param Comptes $compteOperationCaisse
     * @param Comptes $compteProduit
     * @param $libelle
     * @param $montant
     * @return Transactions
     */
    public function genComptaRecettes(Utilisateurs $utilisateur, Comptes $compteOperationCaisse, Comptes $compteProduit, $libelle, $montant)
    {
        $transaction=$this->initTransaction($utilisateur,$libelle,$montant);

        if ($transaction->getE()) return $transaction ;

        return $this->debiterCrediter($transaction, $compteOperationCaisse, $compteProduit, $montant);
    }



    /**
     * @Route("/gen", name="gen_compta", methods="GET|POST")
     */

    public function mainTest(): Response
    {
        //// A ENVOYER PAR L APPELANT
        $utilisateur=$this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['login'=>'asanou']);
        
        /////////////////////////////// ECART OUVERTURE DE CAISSE : RETROUR LA TRANSACTION //////////////////////////
        //$compteOperationCaisse=$this->getDoctrine()->getRepository(Caisses::class)->findOneBy(['libelle'=>'PISSY-Caisse 1'])->getIdCompteOperation();
        // $transactions=$this->genComptaEcartOuv($utilisateur,$compteOperationCaisse, 7000);
        ///////////////////////////////////FIN ECART OUVERTURE ////////////////////////////////////////////////////////////////////


        /////////////////////////////// DEPOT ET RETRAIT :  RETROUR LA TRANSACTION //////////////////////////
        //$compteOperationCaisse=$this->getDoctrine()->getRepository(Caisses::class)->findOneBy(['libelle'=>'PISSY-Caisse 1'])->getIdCompteOperation();
        //$compteClient=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['intitule'=>'OUEDRAOGO HAMADO - Ordinaire']);
        //$transactions=$this->genComptaDepot($utilisateur,$compteOperationCaisse,$compteClient, 'Depot Cash par LMM', 20000);
        //$transactions=$this->genComptaRetrait($utilisateur,$compteOperationCaisse,$compteClient, 'Retrait Cash par LMM', 1000);
        ///////////////////////////////////FIN


        /////////////////////////////// DEPENSES ET RECETTES :  RETROUR LA TRANSACTION //////////////////////////
        $compteCaisseMD=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['intitule'=>'Caisse menu depenses']);

        //$compteCharge=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['intitule'=>'Charges diverses']);
        //$transactions=$this->genComptaDepenses($utilisateur,$compteCaisseMD,$compteCharge, 'Achats Internet', 10000);

        $compteRecette=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['intitule'=>'Recettes diverses']);
        $transactions=$this->genComptaRecettes($utilisateur,$compteCaisseMD,$compteRecette, 'Vente antivirus', 150000);
        ///////////////////////////////////FIN
        return $this->render( 'comptMainTest.html.twig',['transactions'=>$transactions]);

    }

}