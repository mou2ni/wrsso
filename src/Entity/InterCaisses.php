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
    const ANNULATION_EN_COURS='XE', ANNULE='XD', VALIDE='VD', VALIDATION_AUTO='VA';
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

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getJourneeCaisseSortant().' => '.$this->getJourneeCaisseSortant();
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

    public function valider(){
        foreach ($this->getIntercaisseEntrants() as $intercaisseEntrant){
            if($intercaisseEntrant->getStatut() == InterCaisses::ANNULATION_EN_COURS){
                $this->addFlash('success', "vous avez une intercaisse en cours d'annulation");
                return $this->redirectToRoute('inter_caisses_index');
            }
            elseif($intercaisseEntrant->getStatut() == InterCaisses::VALIDATION_AUTO)
                $intercaisseEntrant->setStatut(InterCaisses::VALIDE);
        }
        foreach ($this->getIntercaisseSortants() as $intercaisseSortant){
            if($intercaisseSortant->getStatut() == InterCaisses::ANNULATION_EN_COURS){
                $this->addFlash('success', "vous avez une intercaisse en cours d'annulation");
                return $this->redirectToRoute('inter_caisses_demande');
            }
            elseif($intercaisseSortant->getStatut() == InterCaisses::VALIDATION_AUTO)
                $intercaisseSortant->setStatut(InterCaisses::VALIDE);
        }
    }

   }