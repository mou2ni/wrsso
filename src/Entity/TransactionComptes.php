<?php
/**
 * Created by PhpStorm.
 * User: Mouni
 * Date: 22/11/2016
 * Time: 10:39
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Proxies\__CG__\App\Entity\Comptes;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionComptesRepository")
 * @ORM\Table(name="TransactionComptes")
 * @ORM\HasLifecycleCallbacks()
 */
class TransactionComptes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
 
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes" , inversedBy="transactionComptes", cascade={"persist"})
     * @ORM\JoinColumn(name="IdCompte", referencedColumnName="id", nullable=false)
     */
    private $compte;

    /**
     * @ORM\Column(type="string")
     */
    private $numCompte;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $libelle;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\GreaterThanOrEqual(value="0", message="la valeur doit être positive")
     */
    private $mDebit=0;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\GreaterThanOrEqual(value="0", message="la valeur doit être positive")
     */
    private $mCredit=0;

    /*
     * @ORM\Column(type="float", nullable=true)

    private $mSoldeApres;*/


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transactions", inversedBy="transactionComptes", cascade={"persist"})
     * @ORM\JoinColumn(name="IdTransaction", referencedColumnName="id", nullable=false)
     */
    private $transaction;

    /**
     * @ORM\PreUpdate
     */
    public function updateTransaction(){
        //$this->getTransaction()->setUpdatedAt(new \DateTime());
    }


    /**
     * @return Comptes
     */
    public function getCompte()
    {
        return $this->compte;
    }

    /**
     * @param Comptes $compte
     * @return TransactionComptes
     */
    public function setCompte($compte)
    {
        $this->compte = $compte;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumCompte()
    {
        return $this->numCompte;
    }

    /**
     * @param mixed $numCompte
     * @return TransactionComptes
     */
    public function setNumCompte($numCompte)
    {
        $this->numCompte = $numCompte;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMDebit()
    {
        return $this->mDebit;
    }

    /**
     * @param mixed $mDebit
     * @return TransactionComptes
     */
    public function setMDebit($mDebit)
    {
        $this->mDebit = $mDebit;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMCredit()
    {
        return $this->mCredit;
    }

    /**
     * @param mixed $mCredit
     * @return TransactionComptes
     */
    public function setMCredit($mCredit)
    {
        $this->mCredit = $mCredit;
        return $this;
    }

    /**
     * @return Transactions
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param mixed $transaction
     * @return TransactionComptes
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
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
     * @return TransactionComptes
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return TransactionComptes
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }


}