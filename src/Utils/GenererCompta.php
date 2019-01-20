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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class GenererCompta
{
    /**
     * @var e
     *
     */
    private $e;

    private $err_message;

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

    private function initTransaction(Utilisateurs $utilisateur, $libelle, $montant, JourneeCaisses $journeeCaisse=null)
    {
        $transaction=new Transactions();

        //prend la date comptable de la journée caisse ou la date comptable du jour le cas échéant
        $dateComptable=($journeeCaisse)?($journeeCaisse->getDateComptable())?$journeeCaisse->getDateComptable():$this::getDateComptable():$this::getDateComptable();

        //montant=0 alors ressortir avec ERR_ZERO
        if($montant==0 )
        {
            $this->setE($transaction::ERR_ZERO);
            $this->setErrMessage('Ecriture de montant ZERO interdit ! ! !');
            return false;
        }else{
            $transaction->setUtilisateur($utilisateur)->setLibelle($libelle)->setDateTransaction($dateComptable)->setJourneeCaisse($journeeCaisse);
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
            $this->setErrMessage('Ecriture comptable déséquilibrée ! ! !');
            return false;
        }

        //$em=$this->getDoctrine()->getManager();
        $this->em->persist($transaction);
        $this->em->flush();
        return $transaction;
    }

    private function initDepotRetrait(Utilisateurs $utilisateur, $libelle, $montant, JourneeCaisses $journeeCaisse)
    {
        //initiation et controle des conditions de validité de l'appel
        $transaction=$this->initTransaction($utilisateur,$libelle,$montant,$journeeCaisse);

        if (!$transaction) return false;

        //$transaction=$this->initTransaction($utilisateur,$libelle,$montant);
        //if ($transaction->getE()) return $transaction ;
        //Depot avec montant négatif interdit
        if($montant<0)
        {
            $this->setE($transaction::ERR_NEGATIF);
            $this->setErrMessage('Retrait negatif interdit ! ! !');
            return false;
        }else {
            return $transaction;
        }

    }

    private function debiterCrediterSigne(Utilisateurs $utilisateur, Comptes $cptDebitSiPositif, Comptes $cptCreditSiPositif, $libelle, $montant, JourneeCaisses $journeeCaisse=null)
    {
        $transaction=$this->initTransaction($utilisateur,$libelle,$montant,$journeeCaisse);

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
    public function genComptaEcart(Utilisateurs $utilisateur, Caisses $caisse, $libelle, $montant, JourneeCaisses $journeeCaisse)
    {
        $compteOperation=$this->checkCompteOperation($caisse);
        if (!$compteOperation) return false;
        $compteEcartCaisse=$utilisateur->getCompteEcartCaisse();
        if(!$compteEcartCaisse){
            $this->setErrMessage('Compte Ecart caisse utilisateur '.$utilisateur.' NON PARAMETRE.');
            $this->setE(Transactions::ERR_COMPTE_INEXISTANT);
            return false;
        }
        $this->transactions->add($this->debiterCrediterSigne($utilisateur, $compteOperation, $compteEcartCaisse, $libelle.' - '.$utilisateur, $montant,$journeeCaisse ));
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
    public function genComptaDepot( Utilisateurs $utilisateur, Caisses $caisse, Comptes $compteClient, $libelle, $montant,JourneeCaisses $journeeCaisse)
    {
        $compteOperation=$this->checkCompteOperation($caisse);
        if (!$compteOperation) return false;
        $transaction=$this->initDepotRetrait($utilisateur, $libelle, $montant,$journeeCaisse);

        if (!$transaction) {
            //if ($transaction->getE()) {
                return false;
        }
        else { ///modifie par Moumouni
            $depotRetrait = $this->debiterCrediter($transaction, $compteOperation, $compteClient, $montant);
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
    public function genComptaRetrait(Utilisateurs $utilisateur, Caisses $caisse, Comptes $compteClient, $libelle, $montant,JourneeCaisses $journeeCaisse)
    {
        $compteOperation=$this->checkCompteOperation($caisse);
        if (!$compteOperation) return false;

        if($compteClient->getTypeCompte()==Comptes::INTERNE){
            $this->setE(Transactions::ERR_RETRAIT_COMPTE_INTERNE);
            $this->setErrMessage('Retrait interdit sur ce type de compte ! ! !');
            return false;
        }else{
            $transaction=$this->initDepotRetrait($utilisateur, $libelle, $montant, $journeeCaisse);
        }

        if (!$transaction) {
            //if ($transaction->getE()) {
                return false;
        }
        elseif($compteClient->getSoldeCourant()< $montant) {
            $this->setE($transaction::ERR_SOLDE_INSUFISANT);
            $this->setErrMessage('Solde insuffisant pour ce retrait ! ! !');
            return false;
        }else {
            ///modifie par Moumouni
            $depotRetrait = $this->debiterCrediter($transaction, $compteClient, $compteOperation, $montant);
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
    public function genComptaDepenses(Utilisateurs $utilisateur, Caisses $caisse, Comptes $compteCharge, $libelle, $montant, JourneeCaisses $journeeCaisse=null)
    {
        $compteOperation=$this->checkCompteOperation($caisse);
        if (!$compteOperation) return false;

        $transaction=$this->initTransaction($utilisateur,$libelle,$montant,$journeeCaisse);

        if (!$transaction) return false ;

        $this->transactions->add($this->debiterCrediter($transaction, $compteCharge, $compteOperation, $montant));
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
    public function genComptaRecettes(Utilisateurs $utilisateur, Caisses $caisse, Comptes $compteProduit, $libelle, $montant, JourneeCaisses $journeeCaisse=null)
    {
        $compteOperation=$this->checkCompteOperation($caisse);
        if (!$compteOperation) return false;

        $transaction=$this->initTransaction($utilisateur,$libelle,$montant,$journeeCaisse);

        if (!$transaction) return false ;

        $this->transactions->add($this->debiterCrediter($transaction, $compteOperation, $compteProduit, $montant));
        return !$this->getE();
    }


    /**
     * @param Utilisateurs $utilisateur
     * @param Caisses $caisse
     * @param ParamComptables $paramComptable
     * @param $montant
     * @return bool
     */
    public function genComptaCompense(Utilisateurs $utilisateur, Caisses $caisse, $montant, JourneeCaisses $journeeCaisse)
    {
        $compteOperation=$this->checkCompteOperation($caisse);
        if (!$compteOperation) return false;

        $compteCompense=$this->checkCompteAttenteCompense($caisse);
        if (!$compteCompense) return false;

        $this->transactions->add($this->debiterCrediterSigne($utilisateur, $compteOperation, $compteCompense,'Compense attendue -'.$utilisateur,$montant,$journeeCaisse));
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
        $compteOperation=$this->checkCompteOperation($caisse);
        if (!$compteOperation) return false;

        $compteCvdDevise=$this->checkCompteCvd($caisse);
        if (!$compteCvdDevise) return false;

        $this->transactions->add($this->debiterCrediterSigne($utilisateur, $compteOperation,$compteCvdDevise,$utilisateur.' - Contre valeur devises',$montant));
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
        $compteOperationDebit=$this->checkCompteOperation($caisseDebit);
        if (!$compteOperationDebit) return false;
        $compteOperationCredit=$this->checkCompteOperation($caisseCredit);
        if (!$compteOperationCredit) return false;

        $compteIntercaisseDebit=$this->checkCompteIntercaisse($caisseDebit);
        if (!$compteIntercaisseDebit) return false;
        $compteIntercaisseCredit=$this->checkCompteIntercaisse($caisseCredit);
        if (!$compteIntercaisseCredit) return false;

        $transaction=$this->initTransaction($utilisateur,'Intercaissse - '.$utilisateur, $montant);

        if (!$transaction) return false ;
        $this->transactions->add($this->addDebitCreditSign($transaction, $compteOperationDebit, $compteIntercaisseCredit, $montant));
        $this->transactions->add($this->addDebitCreditSign($transaction, $compteIntercaisseDebit, $compteOperationCredit, $montant));
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
    public function genComptaSalaireNet(Utilisateurs $utilisateur, ParamComptables $paramComptable, ArrayCollection $listSalaires, $libelle, $montantTotal, JourneeCaisses $journeeCaisse)
    {
        $transaction=$this->initTransaction($utilisateur,$libelle,$montantTotal, $journeeCaisse);
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

    /**
     * @return \DateTime
     */
    public static function getDateComptable(){
        $dateComptable=new \DateTime();
        $jourSemaine=$dateComptable->format('N');

        //retourne la date du jour ou la date de vendredi si Samedi ou dimanche
        if ($jourSemaine == 6)  {
            $unjour=new \DateInterval('P1D');
            $dateComptable=$dateComptable->sub($unjour);
        }
        if ($jourSemaine == 7)  {
            $deuxjours=new \DateInterval('P2D');
            $dateComptable=$dateComptable->sub($deuxjours);
        }

        return $dateComptable;
    }

    private function checkCompteOperation(Caisses $caisse){
        $compteOperation=$caisse->getCompteOperation();
        if(!$compteOperation){
            $this->setErrMessage('Compte Opération de caisse '.$caisse->getCode().' NON PARAMETRE.');
            $this->setE(Transactions::ERR_COMPTE_INEXISTANT);
            return false;
        }
        return $compteOperation;
    }

    private function checkCompteAttenteCompense(Caisses $caisse){
        $compte=$caisse->getCompteAttenteCompense();
        if(!$compte){
            $this->$this->setErrMessage('Compte Attente compense de caisse '.$caisse->getCode().' NON PARAMETRE.');
            $this->setE(Transactions::ERR_COMPTE_INEXISTANT);
            return false;
        }
        return $compte;
    }

    private function checkCompteCvd(Caisses $caisse){
        $compte=$caisse->getCompteCvDevise();
        if(!$compte){
            $this->setErrMessage('Compte Devise de caisse '.$caisse->getCode().' NON PARAMETRE.');
            $this->setE(Transactions::ERR_COMPTE_INEXISTANT);
            return false;
        }
        return $compte;
    }

    private function checkCompteIntercaisse(Caisses $caisse){
        $compte=$caisse->getCompteIntercaisse();
        if(!$compte){
            $this->setErrMessage('Compte Intercaisse de caisse '.$caisse->getCode().' NON PARAMETRE.');
            $this->setE(Transactions::ERR_COMPTE_INEXISTANT);
            return false;
        }
        return $compte;
    }

    /**
     * @return mixed
     */
    public function getErrMessage()
    {
        return $this->err_message;
    }

    /**
     * @param mixed $err_message
     * @return GenererCompta
     */
    public function setErrMessage($err_message)
    {
        $this->err_message = $err_message;
        return $this;
    }
}