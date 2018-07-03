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
 * @ORM\Table(name="InterCaisses")
 */
class InterCaisses
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idJourneeCaisseSource;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idJourneeCaisseDestination;

    /**
     * @ORM\Column(type="float")
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
    public function getIdJourneeCaisseSource()
    {
        return $this->idJourneeCaisseSource;
    }

    /**
     * @param mixed $idJourneeCaisseSource
     */
    public function setIdJourneeCaisseSource($idJourneeCaisseSource)
    {
        $this->idJourneeCaisseSource = $idJourneeCaisseSource;
    }

    /**
     * @return mixed
     */
    public function getIdJourneeCaisseDestination()
    {
        return $this->idJourneeCaisseDestination;
    }

    /**
     * @param mixed $idJourneeCaisseDestination
     */
    public function setIdJourneeCaisseDestination($idJourneeCaisseDestination)
    {
        $this->idJourneeCaisseDestination = $idJourneeCaisseDestination;
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


   }