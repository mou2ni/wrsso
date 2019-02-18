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
 * @ORM\Entity (repositoryClass="App\Repository\DepotRetraitsRepository")
 */
class DepotRetraits
{
    const STAT_INITIAL='I', STAT_COMPTABILISE='C';

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Transactions")
     * @ORM\JoinColumn(nullable=true)
     */
    private $transaction;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses", inversedBy="depotRetraits", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $journeeCaisse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $compteOperationCaisse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $compteClient;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateOperation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $numCompteSaisie;

    /**
     * @ORM\Column(type="float")
     */
    private $mDepot=0;


    /**
     * @ORM\Column(type="float")
     */
    private $mRetrait=0;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $statut=DepotRetraits::STAT_INITIAL;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return DepotRetraits
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
     * @return DepotRetraits
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
     * @return DepotRetraits
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompteOperationCaisse()
    {
        return $this->compteOperationCaisse;
    }

    /**
     * @param mixed $compteOperationCaisse
     * @return DepotRetraits
     */
    public function setCompteOperationCaisse($compteOperationCaisse)
    {
        $this->compteOperationCaisse = $compteOperationCaisse;
        return $this;
    }

    /**
     * @return Comptes
     */
    public function getCompteClient()
    {
        return $this->compteClient;
    }

    /**
     * @param mixed $compteClient
     * @return DepotRetraits
     */
    public function setCompteClient($compteClient)
    {
        $this->compteClient = $compteClient;
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
     * @return DepotRetraits
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
     * @return DepotRetraits
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMDepot()
    {
        return $this->mDepot;
    }

    /**
     * @param mixed $mDepot
     * @return DepotRetraits
     */
    public function setMDepot($mDepot)
    {
        $this->mDepot = $mDepot;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMRetrait()
    {
        return $this->mRetrait;
    }

    /**
     * @param mixed $mRetrait
     * @return DepotRetraits
     */
    public function setMRetrait($mRetrait)
    {
        $this->mRetrait = $mRetrait;
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
     * @return DepotRetraits
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumCompteSaisie()
    {
        return $this->numCompteSaisie;
    }

    /**
     * @param mixed $numCompteSaisie
     * @return DepotRetraits
     */
    public function setNumCompteSaisie($numCompteSaisie)
    {
        $this->numCompteSaisie = $numCompteSaisie;
        return $this;
    }

    /**
     * @return JourneeCaisses
     */
    public function getJourneeCaisse()
    {
        return $this->journeeCaisse;
    }

    /**
     * @param JourneeCaisses $journeeCaisse
     * @return DepotRetraits
     */
    public function setJourneeCaisse(JourneeCaisses $journeeCaisse)
    {
        $this->journeeCaisse = $journeeCaisse;
        return $this;
    }

}