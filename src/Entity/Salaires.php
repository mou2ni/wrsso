<?php
/**
 * Created by Hamado.
 * Date: 21/01/2019
 * Time: 07:14
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SalairesRepository")
 * @ORM\Table(name="Salaires")
 */
class Salaires
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $transaction;

    /**
     * @ORM\Column(type="date")
     */
    private $periodeSalaire;

    /**
     * @ORM\Column(type="float")
     */
    private $mChargeTotale;

    /**
     * @ORM\Column(type="float")
     */
    private $mRemunerationTotale;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LigneSalaires", mappedBy="salaire", cascade={"persist"})
     */
    private $ligneSalaires;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Salaires
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return Salaires
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPeriodeSalaire()
    {
        return $this->periodeSalaire;
    }

    /**
     * @param mixed $periodeSalaire
     * @return Salaires
     */
    public function setPeriodeSalaire($periodeSalaire)
    {
        $this->periodeSalaire = $periodeSalaire;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMSalaireNetTotal()
    {
        return $this->mSalaireNetTotal;
    }

    /**
     * @param mixed $mSalaireNetTotal
     * @return Salaires
     */
    public function setMSalaireNetTotal($mSalaireNetTotal)
    {
        $this->mSalaireNetTotal = $mSalaireNetTotal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLigneSalaires()
    {
        return $this->ligneSalaires;
    }

    /**
     * @param mixed $ligneSalaires
     * @return Salaires
     */
    public function setLigneSalaires($ligneSalaires)
    {
        $this->ligneSalaires = $ligneSalaires;
        return $this;
    }

    
}