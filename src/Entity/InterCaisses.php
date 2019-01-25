<?php
/**
 * Created by PhpStorm.
 * User: Mouni
 * Date: 22/11/2016
 * Time: 10:39
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity (repositoryClass="App\Repository\IntercaissesRepository")
 * @ORM\Table(name="InterCaisses")
 */
class InterCaisses
{
    const INITIE='I', ANNULE='X', VALIDE='V', VALIDATION_AUTO='VA', COMPTA_CHARGE='CC', COMPTA_PRODUIT='CP';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses" , inversedBy="intercaisseSortants")
     * @ORM\JoinColumn(name="journeeCaisseSortant", referencedColumnName="id", nullable=false)
     */
    private $journeeCaisseSortant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses" , inversedBy="intercaisseEntrants")
     * @ORM\JoinColumn(name="journeeCaisseEntrant", referencedColumnName="id", nullable=false)
     */
    private $journeeCaisseEntrant;

    /*
     * @ORM\ManyToOne(targetEntity="App\Entity\Transactions")
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     */
    private $transaction;

    /**
     * @ORM\Column(type="float")
     */
    private $mIntercaisse;

    //@Assert\GreaterThan(value="0", message="la valeur doit positive")

    /**
     * @ORM\Column(type="string")
     */
    private $statut;


    /**
     * @ORM\Column(type="string")
     */
    private $observations;

    //private $sortant = false;
    
    private $journeeCaissePartenaire;

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getJourneeCaisseSortant().' => '.$this->getJourneeCaisseEntrant();
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
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return JourneeCaisses
     */
    public function getJourneeCaisseSortant()
    {
        return $this->journeeCaisseSortant;
    }

    /**
     * @param JourneeCaisses $journeeCaisseSortant
     * @return InterCaisses
     */
    public function setJourneeCaisseSortant($journeeCaisseSortant)
    {
        $this->journeeCaisseSortant = $journeeCaisseSortant;
        return $this;
    }

    /**
     * @return JourneeCaisses
     */
    public function getJourneeCaisseEntrant()
    {
        return $this->journeeCaisseEntrant;
    }

    /**
     * @param JourneeCaisses $journeeCaisseEntrant
     * @return InterCaisses
     */
    public function setJourneeCaisseEntrant($journeeCaisseEntrant)
    {
        $this->journeeCaisseEntrant = $journeeCaisseEntrant;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getMIntercaisse()
    {
        return $this->mIntercaisse;
    }

    /**
     * @param mixed $mIntercaisse
     */
    public function setMIntercaisse($mIntercaisse)
    {
        $this->mIntercaisse = $mIntercaisse;
    }

    /**
     * @return mixed
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param mixed $statut
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

    /**
     * @return mixed
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * @param mixed $observations
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;
    }

    /**
     * @return bool
     */
    public function isSortant(): bool
    {
        return $this->sortant;
    }

    /**
     * @param bool $sortant
     * @return InterCaisses
     */
    public function setSortant(bool $sortant): InterCaisses
    {
        $this->sortant = $sortant;
        return $this;
    }

    /**
     * @return JourneeCaisses
     */
    public function getJourneeCaissePartenaire()
    {
        return $this->journeeCaissePartenaire;
    }

    /**
     * @param JourneeCaisses $journeeCaissePartenaire
     * @return InterCaisses
     */
    public function setJourneeCaissePartenaire($journeeCaissePartenaire)
    {
        $this->journeeCaissePartenaire = $journeeCaissePartenaire;
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
     * @param Transactions $transaction
     * @return InterCaisses
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
        return $this;
    }


   }