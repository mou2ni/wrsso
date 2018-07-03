<?php
/**
 * Created by PhpStorm.
 * User: Mouni
 * Date: 22/11/2016
 * Time: 10:39
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="TransactionComptes")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes" , inversedBy="Comptes", cascade={"persist"})
     * @ORM\JoinColumn(name="IdCompte", referencedColumnName="id", nullable=false)
     */
    private $compte;

    /**
     * @ORM\Column(type="string")
     */
    private $numCompte;

    /**
     * @ORM\Column(type="float")
     */
    private $mDebit;

    /**
     * @ORM\Column(type="float")
     */
    private $mCredit;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TransactionComptes", inversedBy="transactionComptes", cascade={"persist"})
     * @ORM\JoinColumn(name="IdTransaction", referencedColumnName="id", nullable=false)
     */
    private $transaction;

    /**
     * @return mixed
     */
    public function getCompte()
    {
        return $this->compte;
    }

    /**
     * @param mixed $compte
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
     * @return mixed
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

}