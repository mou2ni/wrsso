<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 27/06/2018
 * Time: 13:37
 */

namespace App\Utils;

use App\Entity\Caisses;
use App\Entity\Comptes;
use App\Entity\ParamComptables;
use App\Entity\Transactions;
use App\Entity\TransactionComptes;
use App\Entity\Utilisateurs;

//use phpDocumentor\Reflection\Types\Boolean;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;

//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;


class GenererCompta
{
    /**
     * @var e
     *
     */
    private $e;

    private $em;
    //private $om;

    /**
     * @var transactions
     *
     */
    private $transactions;

    public function __construct(ObjectManager $objectManager)
    {
        $this->em=$objectManager;
        //$this->om=$objectManager;
        $this->transactions= new ArrayCollection();

    }

    /**
     * @return e
     */
    public function getE()
    {
        return $this->e;
    }

    /**
     * @param e $e
     * @return GenererCompta
     */
    public function setE($e)
    {
        $this->e = $e;
        return $this;
    }

    /**
     * @return Transactions
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * @param Transactions $transactions
     * @return GenererCompta
     */
    public function setTransactions($transactions)
    {
        $this->transactions = $transactions;
        return $this;
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
        if($montant==0 )
        {
            $this->setE($transaction::ERR_ZERO);
            return false;
        }else{
            $transaction->setUtilisateur($utilisateur)->setLibelle($libelle)->setDateTransaction( new \DateTime());
            return $transaction;
        }

    }

    private function debiterCrediter(Transactions $transaction, Comptes $compteDebit, Comptes $compteCredit, $montant)
    {
        //vérification de la non nullité des comptes transmis
        //if ($compteDebit==null or $compteCredit==null) return new Transactions();

        $montant = abs($montant);
        //ajout de ligne d'écriture debit
        $transaction->addTransactionComptes($this->fillTransactionCompte($compteDebit, $montant, true));

        //ajout de ligne d'écriture credit
        $transaction->addTransactionComptes($this->fillTransactionCompte($compteCredit, $montant, false));

        //$this->em=$this->getDoctrine()->getManager();
        $this->em->persist($transaction);
        $this->em->flush();
        return $transaction;
    }

    private function debiterCrediterMultiple(Transactions $transaction, ArrayCollection $compteMontantDebits, ArrayCollection $compteMontantCredits)
    {
        //vérification de la non nullité des comptes transmis
        //if ($compteDebit==null or $compteCredit==null) return new Transactions();
        
        $sommeDebit=0;
        $sommeCredit=0;
        foreach ($compteMontantDebits as $compteMontantDebit){
            $transaction->addTransactionComptes($this->fillTransactionCompte($compteMontantDebit['compte'], $compteMontantDebit['montant'], true));
            $sommeDebit+=$compteMontantDebit['montant'];
        }

        foreach ($compteMontantCredits as $compteMontantCredit){
            $transaction->addTransactionComptes($this->fillTransactionCompte($compteMontantCredit['compte'], $compteMontantCredit['montant'], false));
            $sommeCredit+=$compteMontantCredit['montant'];
        }

        if ($sommeDebit!=$sommeCredit){
            $this->setE($transaction::ERR_DESEQUILIBRE);
            return false;
        }

        //$em=$this->getDoctrine()->getManager();
        $this->em->persist($transaction);
        $this->em->flush();
        return $transaction;
    }

    private function initDepotRetrait(Utilisateurs $utilisateur, $libelle, $montant)
    {
        //initiation et controle des conditions de validité de l'appel
        $transaction=$this->initTransaction($utilisateur,$libelle,$montant);

        if (!$transaction) return false;

        //$transaction=$this->initTransaction($utilisateur,$libelle,$montant);
        //if ($transaction->getE()) return $transaction ;
        //Depot avec montant négatif interdit
        if($montant<0)
        {
            $this->setE($transaction::ERR_NEGATIF);
            return false;
        }else {
            return $transaction;
        }

    }

    private function debiterCrediterSigne(Utilisateurs $utilisateur, Comptes $cptDebitSiPositif, Comptes $cptCreditSiPositif, $libelle, $montant)
    {
        $estPositif=($montant>0);
        $transaction=$this->initTransaction($utilisateur,$libelle,$montant);

        if (!$transaction) return false ;
        //if ($transaction->getE()) return $transaction ;

        if ($estPositif) return $this->debiterCrediter($transaction, $cptDebitSiPositif, $cptCreditSiPositif, $montant);
        else return $this->debiterCrediter($transaction, $cptCreditSiPositif, $cptDebitSiPositif, $montant); //inverser l'ordre des comptes si negatif
    }


    /**
     * @param Utilisateurs $utilisateur
     * @param Caisses $caisse
     * @param $montant
     * @return bool
     */
    public function genComptaEcart(Utilisateurs $utilisateur, Caisses $caisse, $libelle, $montant)
    {
        $this->transactions->add($this->debiterCrediterSigne($utilisateur, $utilisateur->getCompteEcartCaisse(), $caisse->getIdCompteOperation(), $libelle.' - '.$utilisateur, $montant ));
        return !$this->getE();
    }


    /**
     * @param Utilisateurs $utilisateur
     * @param Caisses $caisse
     * @param Comptes $compteClient
     * @param $libelle
     * @param $montant
     * @return bool
     */
    public function genComptaDepot(Utilisateurs $utilisateur, Caisses $caisse, Comptes $compteClient, $libelle, $montant)
    {
        $transaction=$this->initDepotRetrait($utilisateur, $libelle, $montant);

        if (!$transaction) {
            //if ($transaction->getE()) {
                return false;
        }
        else {
            $this->transactions->add($this->debiterCrediter($transaction, $caisse->getIdCompteOperation(), $compteClient, $montant));
            return !$this->getE();
        }

    }


    /**
     * @param Utilisateurs $utilisateur
     * @param Caisses $caisse
     * @param Comptes $compteClient
     * @param $libelle
     * @param $montant
     * @return bool
     */
    public function genComptaRetrait(Utilisateurs $utilisateur, Caisses $caisse, Comptes $compteClient, $libelle, $montant)
    {

        $transaction=$this->initDepotRetrait($utilisateur, $libelle, $montant);

        if (!$transaction) {
            //if ($transaction->getE()) {
                return false;
        }
        elseif($compteClient->getSoldeCourant()< $montant) {
            $this->setE($transaction::ERR_SOLDE_INSUFISANT);
            return false;
        }else {
            //ajout des lignes d'écritures debit et crédit
            $this->transactions->add($this->debiterCrediter($transaction, $compteClient, $caisse->getIdCompteOperation(), $montant));
            return !$this->getE();
        }

    }


    /**
     * @param Utilisateurs $utilisateur
     * @param Caisses $caisse
     * @param Comptes $compteCharge
     * @param $libelle
     * @param $montant
     * @return bool
     */
    public function genComptaDepenses(Utilisateurs $utilisateur, Caisses $caisse, Comptes $compteCharge, $libelle, $montant)
    {
        $transaction=$this->initTransaction($utilisateur,$libelle,$montant);

        if (!$transaction) return false ;

        $this->transactions->add($this->debiterCrediter($transaction, $compteCharge, $caisse->getIdCompteOperation(), $montant));
        return !$this->getE();
    }


    /**
     * @param Utilisateurs $utilisateur
     * @param Caisses $caisse
     * @param Comptes $compteProduit
     * @param $libelle
     * @param $montant
     * @return bool
     */
    public function genComptaRecettes(Utilisateurs $utilisateur, Caisses $caisse, Comptes $compteProduit, $libelle, $montant)
    {
        $transaction=$this->initTransaction($utilisateur,$libelle,$montant);

        if (!$transaction) return false ;

        $this->transactions->add($this->debiterCrediter($transaction, $caisse->getIdCompteOperation(), $compteProduit, $montant));
        return !$this->getE();
    }


    /**
     * @param Utilisateurs $utilisateur
     * @param Caisses $caisse
     * @param ParamComptables $paramComptable
     * @param $montant
     * @return bool
     */
    public function genComptaCompense(Utilisateurs $utilisateur, Caisses $caisse, ParamComptables $paramComptable, $montant)
    {
       $this->transactions->add($this->debiterCrediterSigne($utilisateur,$paramComptable->getCompteCompense(), $caisse->getIdCompteOperation(),'Compense attendue -'.$utilisateur,$montant));
        return !$this->getE();
    }


    /**
     * @param Utilisateurs $utilisateur
     * @param Caisses $caisse
     * @param $montant
     * @return bool
     */
    public function genComptaCvDevise(Utilisateurs $utilisateur, Caisses $caisse, $montant)
    {
        $this->transactions->add($this->debiterCrediterSigne($utilisateur,$caisse->getCompteCvDevise(), $caisse->getIdCompteOperation(),$utilisateur.' - Solde Contre valeur devises',$montant));
        return !$this->getE();

    }


    /**
     * @param Utilisateurs $utilisateur
     * @param Caisses $caisse
     * @param ParamComptables $paramComptable
     * @param $montant
     * @return bool
     */
    public function genComptaIntercaisse(Utilisateurs $utilisateur, Caisses $caisse, ParamComptables $paramComptable, $montant)
    {
        $this->transactions->add($this->debiterCrediterSigne($utilisateur,$caisse->getIdCompteOperation(),$paramComptable->getCompteIntercaisse(),'Solde intercaissse - '.$utilisateur,$montant));
        return !$this->getE();
    }

    /**
     * @param Utilisateurs $utilisateur
     * @param Caisses $caisse
     * @param ParamComptables $paramComptable
     * @param $mIntercaisse
     * @param $mCompense
     * @param $mDevise
     * @param $mEcart
     * @return bool
     */
    public function genComptaFermeture(Utilisateurs $utilisateur, Caisses $caisse, ParamComptables $paramComptable, $mIntercaisse, $mCompense, $mDevise, $mEcart)
    {

        if (!$this->genComptaIntercaisse($utilisateur,$caisse,$paramComptable,$mIntercaisse)) return false;
        if (!$this->genComptaCompense($utilisateur,$caisse,$paramComptable,$mCompense)) return false;
        if (!$this->genComptaCvDevise($utilisateur,$caisse,$mDevise)) return false;
        if (!$this->genComptaEcart($utilisateur,$caisse,'Ecart fermeture',$mEcart)) return false;
        return !$this->getE();

    }

    public function genComptaSalaireNet(Utilisateurs $utilisateur, ParamComptables $paramComptable, ArrayCollection $listSalaires, $libelle, $montantTotal)
    {
        $transaction=$this->initTransaction($utilisateur,$libelle,$montantTotal);
        if ($this->getE()) return false;

        if( $montantTotal<0){
            $this->setE($transaction::ERR_NEGATIF);
            return false;
        }

        $compteMontantDebits=new ArrayCollection();
        $compteMontantDebits->add(['compte'=>$paramComptable->getCompteChargeSalaireNet(),'montant'=>$montantTotal]);

        $this->transactions->add($this->debiterCrediterMultiple($transaction,$compteMontantDebits, $listSalaires));
        return !$this->getE();
    }

/*
    public function mainTest(): Response
    {
        //// A ENVOYER PAR L APPELANT
        $utilisateur=$this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['login'=>'asanou']);
        $caisse=$this->getDoctrine()->getRepository(Caisses::class)->findOneBy(['libelle'=>'PISSY-Caisse 1']);
        $paramComptable=$this->getDoctrine()->getRepository(ParamComptables::class)->findOneBy(['codeStructure'=>'YESBO']);

        //$transactions=array();

        /////////////////////////////// ECART OUVERTURE DE CAISSE : RETROUR LA TRANSACTION //////////////////////////

         $this->genComptaEcart($utilisateur,$caisse, 'Ecart ouverture', 2000);
         $this->genComptaEcart($utilisateur,$caisse, 'Ecart ouverture', -1000);
        ///////////////////////////////////FIN ECART OUVERTURE ////////////////////////////////////////////////////////////////////


        /////////////////////////////// DEPOT ET RETRAIT :  RETROUR LA TRANSACTION //////////////////////////

        $compteClient=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['intitule'=>'OUEDRAOGO HAMADO - Ordinaire']);
        $this->genComptaDepot($utilisateur,$caisse,$compteClient, 'Depot Cash par LMM', 3000000);

        $this->genComptaRetrait($utilisateur,$caisse,$compteClient, 'Retrait Cash par LMM', 100000);

        ///////////////////////////////////FIN


        /////////////////////////////// DEPENSES ET RECETTES :  RETROUR LA TRANSACTION //////////////////////////
        $caisseMD=$this->getDoctrine()->getRepository(Caisses::class)->findOneBy(['libelle'=>'Caisse menu depense']);

        $compteCharge=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['intitule'=>'Charges diverses']);
        $this->genComptaDepenses($utilisateur,$caisseMD,$compteCharge, 'Achats Internet', 50000);

        $compteRecette=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['intitule'=>'Produits divers']);
        $this->genComptaRecettes($utilisateur,$caisseMD,$compteRecette, 'Vente antivirus', 60000);

        ///////////////////////////////////FIN

        /////////////////////////////// COMPENSES //////////////////////////

        $this->genComptaCompense($utilisateur,$caisse,$paramComptable,400000);
        $this->genComptaCompense($utilisateur,$caisse,$paramComptable,-300000);

        ///////////////////////////////////FIN

        /////////////////////////////// DEVISE //////////////////////////

        $this->genComptaCvDevise($utilisateur,$caisse,671000);
        $this->genComptaCvDevise($utilisateur,$caisse,-661000);

        ///////////////////////////////////FIN

        /////////////////////////////// INTER CAISSE //////////////////////////

        $this->genComptaIntercaisse($utilisateur,$caisse,$paramComptable,250000);
        $this->genComptaIntercaisse($utilisateur,$caisse,$paramComptable,-200000);

        ///////////////////////////////////FIN

        /////////////////////////////// FERMETURE CAISSE //////////////////////////

        $this->genComptaFermeture($utilisateur,$caisse,$paramComptable,100000,200000,300000,2000);
        $this->genComptaFermeture($utilisateur,$caisse,$paramComptable,-50000,-100000,-150000,-1000);

        ///////////////////////////////////FIN

        /////////////////////////////// SALAIRES //////////////////////////

        $listSalaires=new ArrayCollection();
        $listSalaires->add(['compte'=>$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['intitule'=>'OUEDRAOGO HAMADO - Salaire']),'montant'=>500000]);
        $listSalaires->add(['compte'=>$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['intitule'=>'OUEDRAOGO Moumouni - Salaire']),'montant'=>900000]);

        $this->genComptaSalaireNet($utilisateur,$paramComptable,$listSalaires,'Salaire du mois de Juillet 2018', 1400000);


        ///////////////////////////////////FIN

        if (! $this->getE()) return $this->render( 'comptMainTest.html.twig',['transactions'=>$this->transactions]);
        else { $transaction=new Transactions();
            $transaction->setLibelle($this->getE());
            //$transaction->setTransactionComptes($this->transactions);
            return $this->render( 'comptMainTest.html.twig',['transactions'=>[$transaction]]);
        }

    }*/

}