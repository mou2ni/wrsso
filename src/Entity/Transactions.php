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
 * @ORM\Entity
 * @ORM\Table(name="Transactions")
 * @ORM\HasLifecycleCallbacks()
 */

class Transactions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateurs")
     * @ORM\JoinColumn(name="idUtilisateur", nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateurs")
     * @ORM\JoinColumn(name="idUtilisateurLast", nullable=false)
     */
    private $utilisateurLast;

    /**
     * @ORM\Column(type="string")
     */
    private $libelle;

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
     * @ORM\OneToMany(targetEntity="App\Entity\TransactionComptes", mappedBy="transaction")
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
    }

    /**
     * @ORM\PrePersist
     */
    public function createDate(){
        $this->setCreatedAt(new \DateTime());
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

    public function addTransactionComptes(TransactionComptes $transactionCompte)
    {
        $this->transactionComptes->add($transactionCompte);
        $transactionCompte->setTransaction($this);
    }

    public function removeTransactionComptes(TransactionComptes $transactionCompte)
    {
        $this->transactionComptes->removeElement($transactionCompte);
    }
    


}