<?php
/**
 * Created by Hamado.
 * Date: 21/01/2019
 * Time: 06:39
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecetteDepensesRepository")
 */
class RecetteDepenses
{
    const STAT_COMPTA='C', STAT_INITIAL='I', STAT_ANNULER='X';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeOperationComptables")
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeOperationComptable;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transactions")
     * @ORM\JoinColumn(nullable=true)
     */
    private $transaction;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses", inversedBy="recetteDepenses", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $journeeCaisse;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateOperation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\Column(type="float")
     */
    private $mRecette=0;


    /**
     * @ORM\Column(type="float")
     */
    private $mDepense=0;

    /**
     * @ORM\Column(type="string")
     */
    private $statut;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estComptant=true;

    private $mSaisie;

    public function __construct()
    {
        $this->dateOperation = new \DateTime();
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
     * @return RecetteDepenses
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @return RecetteDepenses
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
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
     * @return RecetteDepenses
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJourneeCaisse()
    {
        return $this->journeeCaisse;
    }

    /**
     * @param mixed $journeeCaisse
     * @return RecetteDepenses
     */
    public function setJourneeCaisse($journeeCaisse)
    {
        $this->journeeCaisse = $journeeCaisse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateOperation()
    {
        return $this->dateOperation;
    }

    /**
     * @param mixed $dateOperation
     * @return RecetteDepenses
     */
    public function setDateOperation($dateOperation)
    {
        $this->dateOperation = $dateOperation;
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
     * @return RecetteDepenses
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMRecette()
    {
        return $this->mRecette;
    }

    /**
     * @param mixed $mRecette
     * @return RecetteDepenses
     */
    public function setMRecette($mRecette)
    {
        $this->mRecette = $mRecette;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMDepense()
    {
        return $this->mDepense;
    }

    /**
     * @param mixed $mDepense
     * @return RecetteDepenses
     */
    public function setMDepense($mDepense)
    {
        $this->mDepense = $mDepense;
        return $this;
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
     * @return RecetteDepenses
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMSaisie()
    {
        return $this->mSaisie;
    }

    /**
     * @param mixed $mSaisie
     * @return RecetteDepenses
     */
    public function setMSaisie($mSaisie)
    {
        if($mSaisie<0) $mSaisie=abs($mSaisie);
        ($this->getTypeOperationComptable()->getEstCharge())?$this->setMDepense($mSaisie):$this->setMRecette($mSaisie);
        $this->mSaisie = $mSaisie;
        return $this;
    }

    /**
     * @return TypeOperationComptables
     */
    public function getTypeOperationComptable()
    {
        return $this->typeOperationComptable;
    }

    /**
     * @param mixed $typeOperationComptable
     * @return RecetteDepenses
     */
    public function setTypeOperationComptable($typeOperationComptable)
    {
        $this->typeOperationComptable = $typeOperationComptable;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEstComptant()
    {
        return $this->estComptant;
    }

    /**
     * @param mixed $estComptant
     * @return RecetteDepenses
     */
    public function setEstComptant($estComptant)
    {
        $this->estComptant = $estComptant;
        return $this;
    }

    

}