<?php
/**
 * Created by PhpStorm.
 * User: Mouni
 * Date: 22/11/2016
 * Time: 10:39
 */

namespace App\Entity;


//use App\Utils\GenererCompta;
//use App\Entity\RecetteDepenses;
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
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses" ,  cascade={"persist"})
     */
    private $journeeCaisseInitiateur;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Transactions", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     */
    private $transaction;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecetteDepenses", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     */
    private $recetteDepense;

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
        if($mIntercaisse>0){
            $this->setJourneeCaisseEntrant($this->getJourneeCaisseInitiateur());
            $this->setJourneeCaisseSortant($this->getJourneeCaissePartenaire());
        }else{
            $this->setJourneeCaisseSortant($this->getJourneeCaisseInitiateur());
            $this->setJourneeCaisseEntrant($this->getJourneeCaissePartenaire());
        }
        $this->mIntercaisse = abs($mIntercaisse);
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

    /**
     * @return mixed
     */
    public function getJourneeCaisseInitiateur()
    {
        return $this->journeeCaisseInitiateur;
    }

    /**
     * @param mixed $journeeCaisseInitiateur
     * @return InterCaisses
     */
    public function setJourneeCaisseInitiateur($journeeCaisseInitiateur)
    {
        $this->journeeCaisseInitiateur = $journeeCaisseInitiateur;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecetteDepense()
    {
        return $this->recetteDepense;
    }

    /**
     * @param mixed $transRecetteDepense
     * @return InterCaisses
     */
    public function setRecetteDepense($recetteDepense)
    {
        $this->recetteDepense = $recetteDepense;
        return $this;
    }
    
   }