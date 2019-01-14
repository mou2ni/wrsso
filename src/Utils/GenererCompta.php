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
use App\Entity\JourneeCaisses;
use App\Entity\ParamComptables;
use App\Entity\Transactions;
use App\Entity\TransactionComptes;
use App\Entity\Utilisateurs;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;


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
        $transaction=$this->initTransaction($utilisateur,$libelle,$montant);

        if (!$transaction) return false ;

        //ajouter debit credit
        return $this->addDebitCreditSign($transaction, $cptDebitSiPositif, $cptCreditSiPositif, $montant);

    }

    private function addDebitCreditSign($transaction, $cptDebitSiPositif, $cptCreditSiPositif, $montant){
        $estPositif=($montant>0);
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
        $this->transactions->add($this->debiterCrediterSigne($utilisateur, $caisse->getCompteOperation(), $utilisateur->getCompteEcartCaisse(), $libelle.' - '.$utilisateur, $montant ));
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
    public function genComptaDepot( JourneeCaisses $journeeCaisse, Utilisateurs $utilisateur, Caisses $caisse, Comptes $compteClient, $libelle, $montant)
    {
        $transaction=$this->initDepotRetrait($utilisateur, $libelle, $montant);

        if (!$transaction) {
            //if ($transaction->getE()) {
                return false;
        }
        else { ///modifie par Moumouni
            $depotRetrait = $this->debiterCrediter($transaction, $caisse->getCompteOperation(), $compteClient, $montant);
            $this->transactions->add($depotRetrait);
            $journeeCaisse->updateM('mDepotClient',$montant);
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
    public function genComptaRetrait(JourneeCaisses $journeeCaisse, Utilisateurs $utilisateur, Caisses $caisse, Comptes $compteClient, $libelle, $montant)
    {

        if($compteClient->getTypeCompte()==Comptes::INTERNE){
            $this->setE(Transactions::ERR_RETRAIT_COMPTE_INTERNE);
            return false;
        }else{
            $transaction=$this->initDepotRetrait($utilisateur, $libelle, $montant);
        }

        if (!$transaction) {
            //if ($transaction->getE()) {
                return false;
        }
        elseif($compteClient->getSoldeCourant()< $montant) {
            $this->setE($transaction::ERR_SOLDE_INSUFISANT);
            return false;
        }else {
            ///modifie par Moumouni
            $depotRetrait = $this->debiterCrediter($transaction, $compteClient, $caisse->getCompteOperation(), $montant);
            //ajout des lignes d'écritures debit et crédit
            $this->transactions->add($depotRetrait);
            $journeeCaisse->updateM('mRetraitClient',$montant);
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

        $this->transactions->add($this->debiterCrediter($transaction, $compteCharge, $caisse->getCompteOperation(), $montant));
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

        $this->transactions->add($this->debiterCrediter($transaction, $caisse->getCompteOperation(), $compteProduit, $montant));
        return !$this->getE();
    }


    /**
     * @param Utilisateurs $utilisateur
     * @param Caisses $caisse
     * @param ParamComptables $paramComptable
     * @param $montant
     * @return bool
     */
    public function genComptaCompense(Utilisateurs $utilisateur, Caisses $caisse, $montant)
    {
       $this->transactions->add($this->debiterCrediterSigne($utilisateur, $caisse->getCompteOperation(),$caisse->getCompteAttenteCompense(),'Compense attendue -'.$utilisateur,$montant));
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
        $this->transactions->add($this->debiterCrediterSigne($utilisateur, $caisse->getCompteOperation(),$caisse->getCompteCvDevise(),$utilisateur.' - Contre valeur devises',$montant));
        return !$this->getE();

    }


    /**
     * @param Utilisateurs $utilisateur
     * @param Caisses $caisse
     * @param ParamComptables $paramComptable
     * @param $montant
     * @return bool
     */
    public function genComptaIntercaisse(Utilisateurs $utilisateur, Caisses $caisseDebit, Caisses $caisseCredit, $montant)
    {
        $transaction=$this->initTransaction($utilisateur,'Intercaissse - '.$utilisateur, $montant);

        if (!$transaction) return false ;
        $this->transactions->add($this->addDebitCreditSign($transaction, $caisseDebit->getCompteOperation(), $caisseCredit->getCompteIntercaisse(), $montant));
        $this->transactions->add($this->addDebitCreditSign($transaction, $caisseDebit->getCompteIntercaisse(), $caisseCredit->getCompteOperation(), $montant));
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
    /*public function genComptaFermeture(Utilisateurs $utilisateur, Caisses $caisse, ParamComptables $paramComptable, $mIntercaisse, $mCompense, $mDevise, $mEcart)
    {

        if (!$this->genComptaIntercaisse($utilisateur,$caisse,$paramComptable,$mIntercaisse)) return false;
        if (!$this->genComptaCompense($utilisateur,$caisse,$paramComptable,$mCompense)) return false;
        if (!$this->genComptaCvDevise($utilisateur,$caisse,$mDevise)) return false;
        if (!$this->genComptaEcart($utilisateur,$caisse,'Ecart fermeture',$mEcart)) return false;
        return !$this->getE();

    }*/

    /**
     * @param Utilisateurs $utilisateur
     * @param ParamComptables $paramComptable
     * @param ArrayCollection $listSalaires
     * @param $libelle
     * @param $montantTotal
     * @return bool
     */
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
}