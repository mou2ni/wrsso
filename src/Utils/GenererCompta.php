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
use App\Entity\DepotRetraits;
use App\Entity\InterCaisses;
use App\Entity\JournauxComptables;
use App\Entity\JourneeCaisses;
use App\Entity\LigneSalaires;
use App\Entity\ParamComptables;
use App\Entity\RecetteDepenses;
use App\Entity\Transactions;
use App\Entity\TransactionComptes;
use App\Entity\TransfertInternationaux;
use App\Entity\Utilisateurs;
use App\Entity\TypeOperationComptables;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class GenererCompta
{
    //const COMPTE_CAPITAL='1', COMPTE_IMMOBILISATION='2', COMPTE_STOCK='3', COMPTE_TIERS='4', COMPTE_TRESORERIE='5',COMPTE_CHARGE='6',COMPTE_PRODUIT='7';

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
     * @param Comptes $compte
     * @param $montant
     * @param string $libelle
     * @return TransactionComptes
     */
    private function fillTransactionCompte(Comptes $compte, $montant, $libelle=''){
        $mouvement=new TransactionComptes();
        //$mouvement->setTransaction($transaction);
        $mouvement->setCompte($compte);
        $mouvement->setNumCompte($compte->getNumCompte());
        ($montant<0)?$mouvement->setMDebit(abs($montant)):$mouvement->setMCredit(abs($montant));

        return $mouvement->setLibelle($libelle);
    }

    private function initTransaction(Utilisateurs $utilisateur, $libelle, $montant,JournauxComptables $journalComptable, JourneeCaisses $journeeCaisse=null,$dateTime=null, $numPiece=null)
    {
        $transaction=new Transactions();

        //prend la date comptable de la journée caisse ou la date comptable du jour le cas échéant
        $dateComptable=($dateTime)?$dateTime:($journeeCaisse)?($journeeCaisse->getDateComptable())?$journeeCaisse->getDateComptable():$this::getDateComptable():$this::getDateComptable();

        //montant=0 alors ressortir avec ERR_ZERO
        if($montant==0 )
        {
            $this->setE($transaction::ERR_ZERO);
            $this->setErrMessage('Ecriture de montant ZERO interdit ! ! !');
            return false;
        }else{
            $montant = abs($montant);
            $transaction->setUtilisateur($utilisateur)->setLibelle($libelle)->setDateTransaction($dateComptable)
                ->setJournauxComptable($journalComptable)->setJourneeCaisse($journeeCaisse)->setNumPiece($numPiece);
            //->setMCreditTotal($montant)
             //   ->setMDebitTotal($montant);
            return $transaction;
        }

    }

    private function debiterCrediter(Transactions $transaction, Comptes $compteDebit, Comptes $compteCredit, $montant, $numPiece=null)
    {
        //vérification de la non nullité des comptes transmis
        //if ($compteDebit==null or $compteCredit==null) return new Transactions();

        if ($numPiece) $transaction->setNumPiece($numPiece);

        //$montant = abs($montant);
        //ajout de ligne d'écriture debit
        $transaction->addTransactionCompte($this->fillTransactionCompte($compteDebit, -$montant));

        //ajout de ligne d'écriture credit
        $transaction->addTransactionCompte($this->fillTransactionCompte($compteCredit, $montant));

        //$this->em=$this->getDoctrine()->getManager();
        $this->em->persist($transaction);
        //$this->em->flush();
        return $transaction;
    }

    private function initDepotRetrait(Utilisateurs $utilisateur, $libelle, $montant, JournauxComptables $journalComptable, JourneeCaisses $journeeCaisse)
    {
        //initiation et controle des conditions de validité de l'appel
        $transaction=$this->initTransaction($utilisateur,$libelle,$montant,$journalComptable,$journeeCaisse);

        if (!$transaction) return false;

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


    /**
     * @param Utilisateurs $utilisateur
     * @param Caisses $caisse
     * @param JourneeCaisses $journeeCaisse
     * @return bool
     */
    public function genComptaEcart(Utilisateurs $utilisateur, Caisses $caisse, JourneeCaisses $journeeCaisse)
    {
        //ne pas comptabiliser les écarts si caisse ou banque différents de type guichet
        //if ($caisse->getStatut()!=Caisses::GUICHET) return true;

        $compteOperation=$this->checkCompteOperation($caisse);
        if (!$compteOperation) return false;
        $compteEcartCaisse=$utilisateur->getCompteEcartCaisse();
        if(!$compteEcartCaisse){
            $this->setErrMessage('Compte Ecart caisse utilisateur '.$utilisateur.' NON PARAMETRE.');
            $this->setE(Transactions::ERR_COMPTE_INEXISTANT);
            return false;
        }

        $montant=$journeeCaisse->getMEcartFerm()+$journeeCaisse->getMEcartOuv();
        $transaction=$this->initTransaction($utilisateur,'Ecarts - '.$utilisateur,$montant,$caisse->getJournalComptable(), $journeeCaisse);
        if (!$transaction) return false ;

        $transaction->addTransactionCompte($this->fillTransactionCompte($compteOperation, -$journeeCaisse->getMEcartOuv())->setLibelle('Ecart Ouverture'));
        $transaction->addTransactionCompte($this->fillTransactionCompte($compteOperation, -$journeeCaisse->getMEcartFerm())->setLibelle('Ecart Fermeture'));

        $transaction->addTransactionCompte($this->fillTransactionCompte($compteEcartCaisse, $montant));

        $this->em->persist($transaction);
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
        $transaction=$this->initDepotRetrait($utilisateur, $libelle, $montant,$caisse->getJournalComptable(),$journeeCaisse);

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
     * @param DepotRetraits $depotRetrait
     * @param JourneeCaisses $journeeCaisse
     * @return bool
     */
    public function genComptaDepotRetrait(DepotRetraits $depotRetrait, JourneeCaisses $journeeCaisse, $typeOperation){

        ////////////////// vérification des condtions d'appel
        if ($typeOperation != 'encaissement' and $typeOperation != 'decaissement')
            if (!$this->checkConditionsDepotRetrait($depotRetrait)) return false;

        $utilisateur=$journeeCaisse->getUtilisateur();

        $caisse=$journeeCaisse->getCaisse();
        $compteOperation=$this->checkCompteOperation($caisse);
        if (!$compteOperation) return false;

        $journalComptable=$this->checkJournalComptable($caisse);
        if (!$journalComptable) return false;

        $montant=($depotRetrait->getMDepot())?$depotRetrait->getMDepot():$depotRetrait->getMRetrait();
        $transaction=$this->initTransaction($utilisateur,$depotRetrait->getLibelle(),$montant,$journalComptable,$journeeCaisse);

        if ($depotRetrait->getMDepot()){
            $this->debiterCrediter($transaction, $compteOperation, $depotRetrait->getCompteClient(), $montant);
            //$journeeCaisse->updateM('mDepotClient',$montant);
        }else{
            $this->debiterCrediter($transaction, $depotRetrait->getCompteClient(), $compteOperation, $montant);
            //$journeeCaisse->updateM('mRetraitClient',$montant);
        }
        $this->transactions->add($transaction);
        $depotRetrait->setTransaction($transaction);
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
    public function genComptaRetrait(Utilisateurs $utilisateur, Caisses $caisse, Comptes $compteClient, $libelle, $montant,JourneeCaisses $journeeCaisse)
    {
        $compteOperation=$this->checkCompteOperation($caisse);
        if (!$compteOperation) return false;

        if($compteClient->getTypeCompte()==Comptes::INTERNE){
            $this->setE(Transactions::ERR_RETRAIT_COMPTE_INTERNE);
            $this->setErrMessage('Retrait interdit sur ce type de compte ! ! !');
            return false;
        }else{
            $transaction=$this->initDepotRetrait($utilisateur, $libelle, $montant,$caisse->getJournalComptable(), $journeeCaisse);
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
     * @param Comptes $compteTier
     * @param Comptes $compteGestion
     * @param RecetteDepenses $recetteDepense
     * @param JournauxComptables $journalComptable
     * @param JourneeCaisses|null $journeeCaisse
     * @param \DateTime|null $dateTime
     * @return bool
     */
    private function recetteDepenses(Utilisateurs $utilisateur, Comptes $compteTier, Comptes $compteGestion, RecetteDepenses $recetteDepense, JournauxComptables $journalComptable, JourneeCaisses $journeeCaisse=null, \DateTime $dateTime=null)
    {

        if (!$recetteDepense->typageCompteGestion()){
            $this->setErrMessage('Le compte numero ['.$compteGestion->getNumCompte().'] n\'est pas un compte de Gestion (classe 6 ou 7).');
            $this->setE(Transactions::ERR_COMPTE_INEXISTANT);
            return false;
        }

        //dump($recetteDepense); die();
        ///Comptabilisation
        if ($recetteDepense->getMDepense()){
            $this->genEcritureDebitCredit($utilisateur,$compteGestion,$compteTier,$recetteDepense->getLibelle(),$recetteDepense->getMDepense(),$journalComptable, $journeeCaisse);
        }elseif ($recetteDepense->getMRecette()){
            $this->genEcritureDebitCredit($utilisateur,$compteTier,$compteGestion,$recetteDepense->getLibelle(),$recetteDepense->getMRecette(),$journalComptable, $journeeCaisse);
        }else{
            $this->setErrMessage('Comptabilisation Depense, recettes de montant 0 refusé! ! !');
            $this->setE(Transactions::ERR_ZERO);
            return false;
        }
        return true;
    }


    /**
     * @param RecetteDepenses $recetteDepense
     * @param JourneeCaisses $journeeCaisse
     * @return bool
     */
    public function genComptaRecetteDepenseComptant(RecetteDepenses $recetteDepense, JourneeCaisses $journeeCaisse){
        ////////////////// vérification des condtions d'appel
        $utilisateur=$journeeCaisse->getUtilisateur();
        
        $caisse=$journeeCaisse->getCaisse();
        $compteOperation=$this->checkCompteOperation($caisse);
        if (!$compteOperation) return false;

        $journalComptable=$this->checkJournalComptable($caisse);
        if (!$journalComptable) return false;

        $compteGestion=$recetteDepense->getCompteGestion();
        if(!$compteGestion) {
            $compteGestion=$this->checkCompteTypeOperationComptables($recetteDepense->getTypeOperationComptable());
            if (!$compteGestion) return false;
            $recetteDepense->setCompteGestion($compteGestion);
        }

        //dump($recetteDepense); die();
        return $this->recetteDepenses($utilisateur, $compteOperation, $compteGestion, $recetteDepense, $journalComptable,$journeeCaisse);
    }

    /**
     * @param Utilisateurs $utilisateur
     * @param Comptes $compteTier
     * @param TypeOperationComptables $typeOperationComptable
     * @param $libelle
     * @param $montant
     * @param JournauxComptables $journalComptable
     * @return bool
     */
    public function genComptaRecetteDepenseAterme(Utilisateurs $utilisateur, Comptes $compteTier, TypeOperationComptables $typeOperationComptable, $libelle, $montant, JournauxComptables $journalComptable){

        $compteGestion=$this->checkCompteTypeOperationComptables($typeOperationComptable);
        if (!$compteGestion) return false;
        return $this->recetteDepenses($utilisateur,$compteTier,$compteGestion,$libelle,$montant,$journalComptable);
    }

    private function genEcritureDebitCredit(Utilisateurs $utilisateur, Comptes $compteDebit, $compteCredit, $libelle, $montant, JournauxComptables $journalComptable, JourneeCaisses $journeeCaisse=null, \DateTime $dateTime=null)
    {
        if (!$compteDebit or !$compteCredit){
            $this->setE(Transactions::ERR_COMPTE_INEXISTANT);
            $this->setErrMessage('Compte de débit ou crédit non renseigné ! ! !');
        }
        $transaction=$this->initTransaction($utilisateur,$libelle,$montant,$journalComptable, $journeeCaisse,$dateTime);
        if (!$transaction) return false ;
        $this->transactions->add($this->debiterCrediter($transaction, $compteDebit, $compteCredit, $montant));
        return !$this->getE();
    }

    /**
     * @param Utilisateurs $utilisateur
     * @param Caisses $caisse
     * @param JourneeCaisses $journeeCaisse
     * @return bool
     */
    public function genComptaCvdDeviseFerm(Utilisateurs $utilisateur, Caisses $caisse, JourneeCaisses $journeeCaisse){
        $journalComptable=$this->checkJournalComptable($caisse);
        if (!$journalComptable) return false;
        $compteOperation=$this->checkCompteOperation($caisse);
        if (!$compteOperation) return false;
        $compteCvdDevise=$this->checkCompteCvd($caisse);
        if (!$compteCvdDevise) return false;

        $transaction=$this->initTransaction($utilisateur,'Contre valeur devise - '.$utilisateur, $journeeCaisse->getMCvd(),$journalComptable, $journeeCaisse);
        if (!$transaction) return false ;

        if ($caisse->getComptaDetail()){

            //contrepartie groupé dans le compte opération de la caisse
            $transaction->addTransactionCompte($this->fillTransactionCompte($compteOperation, -$journeeCaisse->getMCvd()));
            //lignes détaillées dans le compte contrevaleur devise
            foreach ($journeeCaisse->getDeviseJournees() as $deviseJournee){
                $transaction->addTransactionCompte($this->fillTransactionCompte($compteCvdDevise, $deviseJournee->getMCvdVente()));
                $transaction->addTransactionCompte($this->fillTransactionCompte($compteCvdDevise, $deviseJournee->getMCvdAchat()));
            }
        }else{
            $transaction->addTransactionCompte($this->fillTransactionCompte($compteOperation, -$journeeCaisse->getMCvd()));
            $transaction->addTransactionCompte($this->fillTransactionCompte($compteCvdDevise, $journeeCaisse->getMCvd()));
        }
        $this->em->persist($transaction);
        return true;
    }

    /**
     * @param Utilisateurs $utilisateur
     * @param Caisses $caisse
     * @param JourneeCaisses $journeeCaisse
     * @return bool
     */
    public function genComptaIntercaisseFerm(Utilisateurs $utilisateur, Caisses $caisse, JourneeCaisses $journeeCaisse){
        $journalComptable=$this->checkJournalComptable($caisse);
        if (!$journalComptable) return false;
        $compteOperation=$this->checkCompteOperation($caisse);
        if (!$compteOperation) return false;
        $compteIntercaisse=$this->checkCompteIntercaisse($caisse);
        if (!$compteIntercaisse) return false;

        $transaction=$this->initTransaction($utilisateur,'Intercaissse - '.$utilisateur, $journeeCaisse->getMIntercaisses(),$journalComptable, $journeeCaisse);
        if (!$transaction) return false ;

        if ($caisse->getComptaDetail()){
            $mIntercaisse=0;
            //lignes détaillées dans le compte d'attente intercaisse
            $IntercaisseEntrantValides=$this->em->getRepository(InterCaisses::class)->findIntercaissesValides($journeeCaisse);
            $IntercaisseSortantValides=$this->em->getRepository(InterCaisses::class)->findIntercaissesValides($journeeCaisse, false);
            foreach ($IntercaisseEntrantValides as $intercaisseEntrant){
                $transaction->addTransactionCompte($this->fillTransactionCompte($compteIntercaisse, $intercaisseEntrant->getMIntercaisse(), 'Intercaisse  '.$intercaisseEntrant->getJourneeCaisseSortant()));
                $intercaisseEntrant->setTransaction($transaction);
                $this->em->persist($intercaisseEntrant);
                $mIntercaisse+=$intercaisseEntrant->getMIntercaisse();
            }
            foreach ($IntercaisseSortantValides as $intercaisseSortant){
                $transaction->addTransactionCompte($this->fillTransactionCompte($compteIntercaisse, -$intercaisseSortant->getMIntercaisse(), 'Intercaisse  '.$intercaisseSortant->getJourneeCaisseEntrant()));
                $intercaisseSortant->setTransaction($transaction);
                $this->em->persist($intercaisseSortant);
                $mIntercaisse-=$intercaisseSortant->getMIntercaisse();
            }

            //contrepartie groupé dans le compte operation de la caisse
            $transaction->addTransactionCompte($this->fillTransactionCompte($compteOperation, -$mIntercaisse, 'Solde Intercaisse'));

        }else{
            $transaction->addTransactionCompte($this->fillTransactionCompte($compteOperation, -$journeeCaisse->getMIntercaisses()));
            $transaction->addTransactionCompte($this->fillTransactionCompte($compteIntercaisse, $journeeCaisse->getMIntercaisses()));
        }
        $this->em->persist($transaction);
        return true;
    }

    /**
     * @param Utilisateurs $utilisateur
     * @param Caisses $caisse
     * @param JourneeCaisses $journeeCaisse
     * @return bool
     */
    public function genComptaCompensesFerm(Utilisateurs $utilisateur, Caisses $caisse, JourneeCaisses $journeeCaisse){
        $journalComptable=$this->checkJournalComptable($caisse);
        if (!$journalComptable) return false;
        $compteOperation=$this->checkCompteOperation($caisse);
        if (!$compteOperation) return false;
        $compteCompense=$this->checkCompteAttenteCompense($caisse);
        if (!$compteCompense) return false;

        $transaction=$this->initTransaction($utilisateur,'Compenses - '.$utilisateur, $journeeCaisse->getCompense(),$journalComptable, $journeeCaisse);
        if (!$transaction) return false ;

        if ($caisse->getComptaDetail()){
            $mCompense=0;
            //lignes détaillées dans le compte d'opération
            foreach ($journeeCaisse->getTransfertInternationaux() as $transfert){
                if ($transfert->getSens()==TransfertInternationaux::ENVOI){
                    $transaction->addTransactionCompte($this->fillTransactionCompte($compteCompense, $transfert->getMTransfertTTC(), 'Envoi : '.$caisse->getCode().' - '.$transfert->getId()));
                    $mCompense+=$transfert->getMTransfertTTC();
                }else {
                    $transaction->addTransactionCompte($this->fillTransactionCompte($compteCompense, -$transfert->getMTransfertTTC(), 'Reception : '.$caisse->getCode().' - '.$transfert->getId()));
                    $mCompense-=$transfert->getMTransfertTTC();
                }

                $transfert->setTransaction($transaction);
                $this->em->persist($transfert);
            }
            //contrepartie groupé dans le compte operation de la caisse
            $transaction->addTransactionCompte($this->fillTransactionCompte($compteOperation, -$mCompense,'Solde compense'));

        }else{
            $transaction->addTransactionCompte($this->fillTransactionCompte($compteOperation, -$journeeCaisse->getCompense()));
            //$transaction->addTransactionCompte($this->fillTransactionCompte($compteOperation, $journeeCaisse->getMReceptionTrans()));
            $transaction->addTransactionCompte($this->fillTransactionCompte($compteCompense, $journeeCaisse->getMEmissionTrans(), 'Total Envoi '.$caisse->getCode()));
            $transaction->addTransactionCompte($this->fillTransactionCompte($compteCompense, -$journeeCaisse->getMReceptionTrans(), 'Total Reception'.$caisse->getCode()));
        }
        $this->em->persist($transaction);
        return true;
    }

    /**
     * @param JourneeCaisses $journeeCaisse
     * @return bool
     */
    public function genComptaFermeture(JourneeCaisses $journeeCaisse){
        $utilisateur=$journeeCaisse->getUtilisateur();
        $caisse=$journeeCaisse->getCaisse();

        //Compenses
        if ($journeeCaisse->getCompense()!=0)
            if ( ! $this->genComptaCompensesFerm($utilisateur,$caisse,$journeeCaisse)) {
            return false;
            }

        //Intercaisses
        if ($journeeCaisse->getMIntercaisses()!=0)
            if (!$this->genComptaIntercaisseFerm($utilisateur,$caisse,$journeeCaisse)) {
                return false;
            }

        //Contre valeur devises
        if ($journeeCaisse->getMCvd()!=0)
            if (!$this->genComptaCvdDeviseFerm($utilisateur,$caisse, $journeeCaisse)){
                return false;
            }

        //ecart de caisse
        if ($journeeCaisse->getMEcartOuv()!=0 or $journeeCaisse->getMEcartFerm()!=0)
            if (!$this->genComptaEcart($utilisateur,$caisse,$journeeCaisse)){
                return false;
            }

        return true;
    }

    /**
     * @param Utilisateurs $utilisateur
     * @param ParamComptables $paramComptable
     * @param LigneSalaires $ligneSalaire
     * @param $periodeSalaire
     * @param JourneeCaisses $journeeCaisse
     * @return Transactions|bool
     */
    public function genComptaLigneSalaire(Utilisateurs $utilisateur, ParamComptables $paramComptable, LigneSalaires $ligneSalaire, $periodeSalaire, JourneeCaisses $journeeCaisse){

        if (!$this->isSetParamComptablesSalaire($paramComptable)) return false;
        $mTotalCharge=$ligneSalaire->getMChargeTotal();

        $transaction=$this->initTransaction($utilisateur,'Salaire de '.$ligneSalaire->getCollaborateur().' - '.$periodeSalaire, $mTotalCharge, $paramComptable->getJournalPaye(), $journeeCaisse, new \DateTime());
        if ($this->getE()) return false;
        if( $mTotalCharge<0){
            $this->setE($transaction::ERR_NEGATIF);
            return false;
        }
        if (!$transaction) return false ;
        //Débit comptes Charges
        $transaction->addTransactionCompte($this->fillTransactionCompte($paramComptable->getCompteChargeBaseSalaire(), -$ligneSalaire->getMSalaireBase(), 'Salaire de Base'));
        $transaction->addTransactionCompte($this->fillTransactionCompte($paramComptable->getCompteChargeLogeSalaire(), -$ligneSalaire->getMIndemLogement(), 'Indemnités de logement'));
        $transaction->addTransactionCompte($this->fillTransactionCompte($paramComptable->getCompteChargeFonctSalaire(), -$ligneSalaire->getMIndemFonction(), 'Indemnités de fonction'));
        $transaction->addTransactionCompte($this->fillTransactionCompte($paramComptable->getCompteChargeTranspSalaire(), -$ligneSalaire->getMIndemTransport(), 'Indemnités de transport'));
        $transaction->addTransactionCompte($this->fillTransactionCompte($paramComptable->getCompteChargeIndemSalaire(), -$ligneSalaire->getMIndemAutres(), 'Autres primes et indemnités'));
        $transaction->addTransactionCompte($this->fillTransactionCompte($paramComptable->getCompteChargeIndemSalaire(), -$ligneSalaire->getMHeureSup(), 'Heures supplémentaires'));
        $transaction->addTransactionCompte($this->fillTransactionCompte($paramComptable->getCompteChargeCotiPatronale(), -$ligneSalaire->getMSecuriteSocialePatronal(), 'Sécurité sociale patronale'));
        $transaction->addTransactionCompte($this->fillTransactionCompte($paramComptable->getCompteTaxeSalaire(), -$ligneSalaire->getMTaxePatronale(), 'Taxe patronale sur salaire'));

        //crédit comptes tiers
        $transaction->addTransactionCompte($this->fillTransactionCompte($paramComptable->getCompteOrgaImpotSalaire(), $ligneSalaire->getMImpotSalarie(), 'Impots sur salaires'));
        $transaction->addTransactionCompte($this->fillTransactionCompte($paramComptable->getCompteOrgaTaxeSalaire(), $ligneSalaire->getMTaxePatronale(), 'Taxes patronales sur salaires'));
        $transaction->addTransactionCompte($this->fillTransactionCompte($paramComptable->getCompteOrgaSocial(), $ligneSalaire->getMSecuriteSocialeSalarie(), 'Sécurité sociale part salarié'));
        $transaction->addTransactionCompte($this->fillTransactionCompte($paramComptable->getCompteOrgaSocial(), $ligneSalaire->getMSecuriteSocialePatronal(), 'Sécurité sociale part patronale'));
        $compteRemunerationDue=($ligneSalaire->getCompteRemunerationDue())?$ligneSalaire->getCompteRemunerationDue():$paramComptable->getCompteRemunerationDue();
        $transaction->addTransactionCompte($this->fillTransactionCompte($compteRemunerationDue, $ligneSalaire->getMNet(), 'Remunération nette due'));

        //dump($transaction);
        $this->transactions->add($transaction);
        if ($transaction->isDesequilibre()){
            $this->setE(Transactions::ERR_DESEQUILIBRE);
            $this->setErrMessage('Ecriture comptable déséquibrée');
            return false;
        }
        $this->em->persist($transaction);
        return $transaction;
    }


    /**
     * @return \DateTime
     */
    public  function getDateComptable(){
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

    private function checkJournalComptable(Caisses $caisse){
        $journalComptable=$caisse->getJournalComptable();
        if(!$journalComptable){
            $this->setErrMessage('Journal comptable de caisse ['.$caisse->getCode().'] NON PARAMETRE.');
            $this->setE(Transactions::ERR_COMPTE_INEXISTANT);
            return false;
        }
        return $journalComptable;
    }

    private function checkCompteTypeOperationComptables(TypeOperationComptables $typeOperationComptable){
        $compteGestion=$typeOperationComptable->getCompte();
        if (!$compteGestion){
            $this->setErrMessage('Compte non paramétré pour l\'opération comptable '.$this->getTypeOperationComptable()->getLibelle());
            $this->setE(Transactions::ERR_COMPTE_INEXISTANT);
            return false;
        }

        return $compteGestion;
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

    private function isSetParamComptablesSalaire(ParamComptables $paramComptable){
        if (! $paramComptable->getCompteRemunerationDue()){
            $this->setE(Transactions::ERR_COMPTE_INEXISTANT);
            $this->setErrMessage('Compte REMUNERATION DUE non parametré dans Paramètres comptables');
            return false;
        }
        if (! $paramComptable->getCompteChargeCotiPatronale()){
            $this->setE(Transactions::ERR_COMPTE_INEXISTANT);
            $this->setErrMessage('Compte CHARGE SOCIALE PATRONALE  non parametré dans Paramètres comptables');
            return false;
        }
        if (! $paramComptable->getCompteChargeIndemSalaire()){
            $this->setE(Transactions::ERR_COMPTE_INEXISTANT);
            $this->setErrMessage('Compte AUTRES INDEMNITES  non parametré dans Paramètres comptables');
            return false;
        }
        if (! $paramComptable->getCompteChargeBaseSalaire()){
            $this->setE(Transactions::ERR_COMPTE_INEXISTANT);
            $this->setErrMessage('Compte CHARGE SALAIRE DE BASE non parametré dans Paramètres comptables');
            return false;
        }
        if (! $paramComptable->getCompteChargeLogeSalaire()){
            $this->setE(Transactions::ERR_COMPTE_INEXISTANT);
            $this->setErrMessage('Compte CHARGE INDEMNITE DE LOGEMENT non parametré dans Paramètres comptables');
            return false;
        }
        if (! $paramComptable->getCompteChargeFonctSalaire()){
            $this->setE(Transactions::ERR_COMPTE_INEXISTANT);
            $this->setErrMessage('Compte CHARGE INDEMNITE DE FONCTION non parametré dans Paramètres comptables');
            return false;
        }
        if (! $paramComptable->getCompteChargeTranspSalaire()){
            $this->setE(Transactions::ERR_COMPTE_INEXISTANT);
            $this->setErrMessage('Compte CHARGE INDEMNITE DE TRANSPORT non parametré dans Paramètres comptables');
            return false;
        }
        if (! $paramComptable->getCompteOrgaImpotSalaire()){
            $this->setE(Transactions::ERR_COMPTE_INEXISTANT);
            $this->setErrMessage('Compte ORGANISME IMPOT SUR SALAIRE non parametré dans Paramètres comptables');
            return false;
        }
        if (! $paramComptable->getCompteOrgaSocial()){
            $this->setE(Transactions::ERR_COMPTE_INEXISTANT);
            $this->setErrMessage('Compte ORGANISME SECURITE SOCIAL non parametré dans Paramètres comptables');
            return false;
        }
        if (! $paramComptable->getCompteOrgaTaxeSalaire()){
            $this->setE(Transactions::ERR_COMPTE_INEXISTANT);
            $this->setErrMessage('Compte ORGANISME TAXE PATRONALE SALAIRE non parametré dans Paramètres comptables');
            return false;
        }

        return true;
    }

    private function checkConditionsDepotRetrait(DepotRetraits $depotRetrait){
        //montant négatif
        if ($depotRetrait->getMDepot() < 0 or $depotRetrait->getMRetrait() < 0){
            $this->setE(Transactions::ERR_NEGATIF);
            $this->setErrMessage('Montant négatif refusé. Veuillez saisir un montant positif.');
            return false;
        }
        if ($depotRetrait->getMRetrait()){
            //solde insuffisant
            if($depotRetrait->getMRetrait()>$depotRetrait->getCompteClient()->getSoldeCourant()) {
                $this->setE(Transactions::ERR_SOLDE_INSUFISANT);
                $this->setErrMessage('Solde du compte insuffisant pour un retrait de ' . $depotRetrait->getMRetrait());
                return false;
            }
            //retrait sur compte interne interdit
            if($depotRetrait->getCompteClient()->getTypeCompte()==Comptes::INTERNE){
                $this->setE(Transactions::ERR_RETRAIT_COMPTE_INTERNE);
                $this->setErrMessage('Retrait refusé sur compte interne '.$depotRetrait->getMRetrait());
                return false;
            }
        }
        return true;
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