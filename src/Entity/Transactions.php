<?php
/**
 * Created by PhpStorm.
 * User: Mouni
 * Date: 22/11/2016
 * Time: 10:39
 */

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionsRepository")
 * @ORM\Table(name="Transactions")
 * @ORM\HasLifecycleCallbacks()
 */

class Transactions
{
    const ERR_SOLDE_INSUFISANT=1, ERR_NEGATIF=2, ERR_ZERO=3, ERR_DESEQUILIBRE=4, ERR_RETRAIT_COMPTE_INTERNE=5, ERR_COMPTE_INEXISTANT=6, ERR_COMPTE_INCOHERANT=7;
    
    private $e;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateurs", cascade={"persist"})
     * @ORM\JoinColumn(name="idUtilisateur", nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses", inversedBy="transactions", cascade={"persist"} )
     * @ORM\JoinColumn(name="idJourneeCaisse", nullable=true)
     */
    private $journeeCaisse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JournauxComptables", inversedBy="transactions", cascade={"persist"} )
     * @ORM\JoinColumn(nullable=true)
     */
    private $journauxComptable;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateurs")
     * @ORM\JoinColumn(name="idUtilisateurLast", nullable=true)
     */
    private $utilisateurLast;

    /**
     * @ORM\Column(type="string")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=50, unique=true, nullable=true)
     */
    private $numPiece;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $mDebitTotal;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $mCreditTotal;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTransaction;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TransactionComptes", mappedBy="transaction", cascade={"persist", "remove"})
     */
    private $transactionComptes;


    public function __construct()
    {
       $this->transactionComptes = new ArrayCollection();
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate(){
        $this->setUpdatedAt(new \DateTime());
        //$this->maintenir();
    }

    /**
     * @ORM\PrePersist
     */
    public function createDate(){
        $this->setCreatedAt(new \DateTime());
        $this->updateDate();
        //$this->maintenir();
    }

    /**
     * @return mixed
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * @param mixed $utilisateur
     * @return Transactions
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUtilisateurLast()
    {
        return $this->utilisateurLast;
    }

    /**
     * @param mixed $utilisateurLast
     * @return Transactions
     */
    public function setUtilisateurLast($utilisateurLast)
    {
        $this->utilisateurLast = $utilisateurLast;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param mixed $libelle
     * @return Transactions
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateTransaction()
    {
        return $this->dateTransaction;
    }

    /**
     * @param mixed $dateTransaction
     * @return Transactions
     */
    public function setDateTransaction($dateTransaction)
    {
        $this->dateTransaction = $dateTransaction;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     * @return Transactions
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     * @return Transactions
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransactionComptes()
    {
        return $this->transactionComptes;
    }

    /**
     * @param mixed $transactionComptes
     * @return Transactions
    */
    public function setTransactionComptes($transactionComptes)
    {
        $this->transactionComptes = $transactionComptes;
        return $this;
    }

    public function addTransactionCompte(TransactionComptes $transactionCompte)
    {
        if ($transactionCompte->getMCredit() ==0 and $transactionCompte->getMDebit()==0) return $this;

        $this->transactionComptes->add($transactionCompte);
        $transactionCompte->setTransaction($this);
        $compte=$transactionCompte->getCompte();
        $compte->setSoldeCourant($compte->getSoldeCourant()+$transactionCompte->getMCredit()-$transactionCompte->getMDebit());
        //$transactionCompte->setMSoldeApres($compte->getSoldeCourant());
        $transactionCompte->setCompte($compte);
        $transactionCompte->setNumCompte($compte->getNumCompte());
        $this->mCreditTotal+=$transactionCompte->getMCredit();
        $this->mDebitTotal+=$transactionCompte->getMDebit();
        return $this;
    }

    public function removeTransactionCompte(TransactionComptes $transactionCompte)
    {
        $this->transactionComptes->removeElement($transactionCompte);

        $compte=$transactionCompte->getCompte();
        $compte->setSoldeCourant($compte->getSoldeCourant()-$transactionCompte->getMCredit()+$transactionCompte->getMDebit());
        $this->mCreditTotal-=$transactionCompte->getMCredit();
        $this->mDebitTotal-=$transactionCompte->getMDebit();
    }

    public function removeAllTransactionCompte(){
        foreach ($this->getTransactionComptes() as $transactionCompte){
            $this->removeTransactionCompte($transactionCompte);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getE()
    {
        return $this->e;
    }

    /**
     * @param mixed $e
     * @return Transactions
     */
    public function setE($e)
    {
        $this->e = $e;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Transactions
     */
    public function setId($id)
    {
        $this->id = $id;
        //if(!$this->getNumPiece()) $this->setNumPiece($id);
        return $this;
    }

    /**
     * @return JourneeCaisses
     */
    public function getJourneeCaisse()
    {
        return $this->journeeCaisse;
    }

    /**
     * @param mixed $journeeCaisse
     * @return Transactions
     */
    public function setJourneeCaisse($journeeCaisse)
    {
        $this->journeeCaisse = $journeeCaisse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumPiece()
    {
        $numPiece=($this->numPiece)?$this->numPiece:$this->getId();
        return $numPiece;
    }

    /**
     * @param mixed $numPiece
     * @return Transactions
     */
    public function setNumPiece($numPiece)
    {
        $this->numPiece = $numPiece;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMDebitTotal()
    {
        return $this->mDebitTotal;
    }

    /**
     * @param mixed $mDebitTotal
     * @return $this
     */
    public function setMDebitTotal($mDebitTotal)
    {
        $this->mDebitTotal = $mDebitTotal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMCreditTotal()
    {
        return $this->mCreditTotal;
    }

    /**
     * @param mixed $mCreditTotal
     */
    public function setMCreditTotal($mCreditTotal)
    {
        $this->mCreditTotal = $mCreditTotal;
        return $this;
    }


    /**
     * @return mixed
     */
    public function isDesequilibre()
    {
        return $this->getMDebitTotal()!=$this->getMCreditTotal();
    }

    /**
     * @return JournauxComptables
     */
    public function getJournauxComptable()
    {
        return $this->journauxComptable;
    }

    /**
     * @param JournauxComptables $journauxComptable
     * @return Transactions
     */
    public function setJournauxComptable($journauxComptable)
    {
        $this->journauxComptable = $journauxComptable;
        return $this;
    }


    public function maintenir(){
        $this->mCreditTotal=0;
        $this->mDebitTotal=0;
        foreach ( $this->getTransactionComptes() as $transactionCompte) {
            $this->mCreditTotal+=$transactionCompte->getMCredit();
            $this->mDebitTotal+=$transactionCompte->getMDebit();
        }
    }
}