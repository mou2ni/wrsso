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
use App\Entity\ParamComptables;
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

    private function debiterCrediterSigne(Utilisateurs $utilisateur, Comptes $cptDebitSiPositif, Comptes $cptCreditSiPositif, $libelle, $montant)
    {
        $estPositif=($montant>0);
        $transaction=$this->initTransaction($utilisateur,$libelle,$montant);

        if ($transaction->getE()) return $transaction ;

        if ($estPositif) return $this->debiterCrediter($transaction, $cptDebitSiPositif, $cptCreditSiPositif, $montant);
        else return $this->debiterCrediter($transaction, $cptCreditSiPositif, $cptDebitSiPositif, $montant); //inverser l'ordre des comptes si negatif
    }


    /**
     * @param Utilisateurs $utilisateur
     * @param Caisses $caisse
     * @param $montant
     * @return Transactions
     */
    public function genComptaEcartOuv(Utilisateurs $utilisateur, Caisses $caisse, $montant)
    {
        return $this->debiterCrediterSigne($utilisateur, $utilisateur->getCompteEcartCaisse(), $caisse->getIdCompteOperation(), 'Ecart ouverture constaté par '.$utilisateur, $montant );
        /*//initiation et controle des conditions de validité de l'appel
        $transaction=$this->initTransaction($utilisateur,"Ecart d'ouverture",$montant);

        if ($transaction->getE()) return $transaction ;

        //$compteEcartUtilisateur=$utilisateur->getCompteEcartCaisse();

        //ajout des lignes de debit et crédit : comptes en fonction du signe de l'écart
        ($montant>0)?$transaction=$this->debiterCrediter($transaction,$utilisateur->getCompteEcartCaisse(), $caisse->getIdCompteOperation(),$montant)
                     :$transaction=$this->debiterCrediter($transaction,$caisse->getIdCompteOperation(),$utilisateur->getCompteEcartCaisse(),$montant);

        return $transaction;*/
    }


    /**
     * @param Utilisateurs $utilisateur
     * @param Caisses $caisse
     * @param Comptes $compteClient
     * @param $libelle
     * @param $montant
     * @return Transactions
     */
    public function genComptaDepot(Utilisateurs $utilisateur, Caisses $caisse, Comptes $compteClient, $libelle, $montant)
    {
        $transaction=$this->initDepotRetrait($utilisateur, $libelle, $montant);

        if ($transaction->getE()) {
            return $transaction;
        }
        else {
            return $this->debiterCrediter($transaction, $caisse->getIdCompteOperation(), $compteClient, $montant);
        }

    }


    /**
     * @param Utilisateurs $utilisateur
     * @param Caisses $caisse
     * @param Comptes $compteClient
     * @param $libelle
     * @param $montant
     * @return Transactions
     */
    public function genComptaRetrait(Utilisateurs $utilisateur, Caisses $caisse, Comptes $compteClient, $libelle, $montant)
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
            return $this->debiterCrediter($transaction, $compteClient, $caisse->getIdCompteOperation(), $montant);
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
    public function genComptaDepenses(Utilisateurs $utilisateur, Caisses $caisse, Comptes $compteCharge, $libelle, $montant)
    {
        $transaction=$this->initTransaction($utilisateur,$libelle,$montant);

        if ($transaction->getE()) return $transaction ;

        return $this->debiterCrediter($transaction, $compteCharge, $caisse->getIdCompteOperation(), $montant);
    }

    /**
     * @param Utilisateurs $utilisateur
     * @param Caisses $caisse
     * @param Comptes $compteProduit
     * @param $libelle
     * @param $montant
     * @return Transactions
     */
    public function genComptaRecettes(Utilisateurs $utilisateur, Caisses $caisse, Comptes $compteProduit, $libelle, $montant)
    {
        $transaction=$this->initTransaction($utilisateur,$libelle,$montant);

        if ($transaction->getE()) return $transaction ;

        return $this->debiterCrediter($transaction, $caisse->getIdCompteOperation(), $compteProduit, $montant);
    }

    /**
     * @param Utilisateurs $utilisateur
     * @param Caisses $caisse
     * @param $montant
     * @return Transactions
     */
    public function genComptaCompense(Utilisateurs $utilisateur, Caisses $caisse, ParamComptables $paramComptable, $montant)
    {
       return $this->debiterCrediterSigne($utilisateur,$paramComptable->getCompteCompense(), $caisse->getIdCompteOperation(),$utilisateur.' - Compense attendue',$montant);
    }

    /**
     * @param Utilisateurs $utilisateur
     * @param Caisses $caisse
     * @param $montant
     * @return Transactions
     */
    public function genComptaCvDevise(Utilisateurs $utilisateur, Caisses $caisse, $montant)
    {
        return $this->debiterCrediterSigne($utilisateur,$caisse->getCompteCvDevise(), $caisse->getIdCompteOperation(),$utilisateur.' - Solde Contre valeur devises',$montant);

    }

    public function genComptaIntercaisse(Utilisateurs $utilisateur, Caisses $caisse, ParamComptables $paramComptable, $montant)
    {
        $this->debiterCrediterSigne($utilisateur,$caisse->getIdCompteOperation(),$paramComptable->getCompteCompense(),$utilisateur.' - Solde intercaissse',$montant);
    }

    /**
     * @Route("/gen", name="gen_compta", methods="GET|POST")
     */

    public function mainTest(): Response
    {
        //// A ENVOYER PAR L APPELANT
        $utilisateur=$this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['login'=>'asanou']);
        $caisse=$this->getDoctrine()->getRepository(Caisses::class)->findOneBy(['libelle'=>'PISSY-Caisse 1']);

        $transactions=array();

        /////////////////////////////// ECART OUVERTURE DE CAISSE : RETROUR LA TRANSACTION //////////////////////////

        $transaction=$this->genComptaEcartOuv($utilisateur,$caisse, 1000);
        $transactions[]=$transaction;
        ///////////////////////////////////FIN ECART OUVERTURE ////////////////////////////////////////////////////////////////////


        /////////////////////////////// DEPOT ET RETRAIT :  RETROUR LA TRANSACTION //////////////////////////

        $compteClient=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['intitule'=>'OUEDRAOGO HAMADO - Ordinaire']);
        $transaction=$this->genComptaDepot($utilisateur,$caisse,$compteClient, 'Depot Cash par LMM', 3000000);
        $transactions[]=$transaction;
        $transaction=$this->genComptaRetrait($utilisateur,$caisse,$compteClient, 'Retrait Cash par LMM', 100000);
        $transactions[]=$transaction;
        ///////////////////////////////////FIN


        /////////////////////////////// DEPENSES ET RECETTES :  RETROUR LA TRANSACTION //////////////////////////
        $caisseMD=$this->getDoctrine()->getRepository(Caisses::class)->findOneBy(['libelle'=>'Caisse menu depense']);

        $compteCharge=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['intitule'=>'Charges diverses']);
        $transaction=$this->genComptaDepenses($utilisateur,$caisseMD,$compteCharge, 'Achats Internet', 50000);
        $transactions[]=$transaction;

        $compteRecette=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['intitule'=>'Produits divers']);
        $transaction=$this->genComptaRecettes($utilisateur,$caisseMD,$compteRecette, 'Vente antivirus', 60000);
        $transactions[]=$transaction;
        ///////////////////////////////////FIN

        /////////////////////////////// COMPENSES //////////////////////////

        $transaction=$this->genComptaCompense($utilisateur,$caisse,400000);
        $transactions[]=$transaction;
        $transaction=$this->genComptaCompense($utilisateur,$caisse,-400000);
        $transactions[]=$transaction;
        ///////////////////////////////////FIN

        /////////////////////////////// DEVISE //////////////////////////

        $transaction=$this->genComptaCvDevise($utilisateur,$caisse,671000);
        $transactions[]=$transaction;
        $transaction=$this->genComptaCvDevise($utilisateur,$caisse,-661000);
        $transactions[]=$transaction;
        ///////////////////////////////////FIN

        return $this->render( 'comptMainTest.html.twig',['transactions'=>$transactions]);

    }

}