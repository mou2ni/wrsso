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
 * @ORM\Entity
 * @ORM\Table(name="InterCaisses")
 */
class InterCaisses
{
    const INITIE='I', ANNULE='X', VALIDE='V', VALIDATION_AUTO='VA';
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

    /**
     * @ORM\Column(type="float")
     * @Assert\GreaterThan(value="0", message="la valeur doit positive")
     */
    private $mIntercaisse;

    /**
     * @ORM\Column(type="string")
     */
    private $statut;


    /**
     * @ORM\Column(type="string")
     */
    private $observations;

    private $sortant = false;

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
     * @return mixed
     */
    public function getJourneeCaisseSortant()
    {
        return $this->journeeCaisseSortant;
    }

    /**
     * @param mixed $journeeCaisseSortant
     * @return InterCaisses
     */
    public function setJourneeCaisseSortant($journeeCaisseSortant)
    {
        $this->journeeCaisseSortant = $journeeCaisseSortant;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJourneeCaisseEntrant()
    {
        return $this->journeeCaisseEntrant;
    }

    /**
     * @param mixed $journeeCaisseEntrant
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

/*
    public function valider($statut=InterCaisses::VALIDE){
        $this->setStatut($statut);
        $this->journeeCaisseSortant->updateM('mIntercaisseSortants', $this->mIntercaisse);
        $this->journeeCaisseEntrant->updateM('mIntercaisseEntrants', $this->mIntercaisse);
        //$this->journeeCaisseSortant->setMIntercaisseSortants($this->journeeCaisseSortant->getMIntercaisseSortants()+ $this->mIntercaisse);
        //$this->journeeCaisseEntrant->setMIntercaisseEntrants($this->journeeCaisseEntrant->getMIntercaisseEntrants()+ $this->mIntercaisse);
    }*/

   }