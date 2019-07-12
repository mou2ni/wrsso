<?php
/**
 * Created by Hamado.
 * User: houedraogo
 * Date: 01/04/2019
 * Time: 15:39
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity (repositoryClass="App\Repository\ApproVersementsRepository")
 */
class ApproVersements
{
    const STAT_INITIAL='I', STAT_ANNULE='X', STAT_VALIDE='V', STAT_VALIDATION_AUTO='VA', STAT_COMPTABILISE='C';

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateurs")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateurValidateur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transactions")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $transactionEntrant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transactions")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $transactionSortant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses", inversedBy="approVersementEntrants", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $journeeCaisseEntrant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses", inversedBy="approVersementSortants", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $journeeCaisseSortant;

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
    private $mAppro=0;

    /**
     * @ORM\Column(type="float")
     */
    private $mApproVersement=0;

    /**
     * @ORM\Column(type="float")
     */
    private $mVersement=0;

    private $mSaisie=0;


    /**
     * @ORM\Column(type="string", length=2)
     */
    private $statut=ApproVersements::STAT_INITIAL;


    private $journeeCaissePartenaire;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return ApproVersements
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
     * @return ApproVersements
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUtilisateurValidateur()
    {
        return $this->utilisateurValidateur;
    }

    /**
     * @param mixed $utilisateurValidateur
     * @return ApproVersements
     */
    public function setUtilisateurValidateur($utilisateurValidateur)
    {
        $this->utilisateurValidateur = $utilisateurValidateur;
        return $this;
    }

    /**
     * @return Transactions
     */
    public function getTransactionEntrant()
    {
        return $this->transactionEntrant;
    }

    /**
     * @param mixed $transactionEntrant
     * @return ApproVersements
     */
    public function setTransactionEntrant($transactionEntrant)
    {
        $this->transactionEntrant = $transactionEntrant;
        return $this;
    }

    /**
     * @return Transactions
     */
    public function getTransactionSortant()
    {
        return $this->transactionSortant;
    }

    /**
     * @param mixed $transactionSortant
     * @return ApproVersements
     */
    public function setTransactionSortant($transactionSortant)
    {
        $this->transactionSortant = $transactionSortant;
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
     * @param mixed $journeeCaisseEntrant
     * @return ApproVersements
     */
    public function setJourneeCaisseEntrant($journeeCaisseEntrant)
    {
        $this->journeeCaisseEntrant = $journeeCaisseEntrant;
        return $this;
    }

    /**
     * @return JourneeCaisses
     */
    public function getJourneeCaisseSortant()
    {
        return $this->journeeCaisseSortant;
    }

    /**
     * @param mixed $journeeCaisseSortant
     * @return ApproVersements
     */
    public function setJourneeCaisseSortant($journeeCaisseSortant)
    {
        $this->journeeCaisseSortant = $journeeCaisseSortant;
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
     * @return ApproVersements
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
     * @return ApproVersements
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMAppro()
    {
        return $this->mAppro;
    }

    /**
     * @param mixed $mAppro
     * @return ApproVersements
     */
    public function setMAppro($mAppro)
    {
        $this->mAppro = $mAppro;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMVersement()
    {
        return $this->mVersement;
    }

    /**
     * @param mixed $mVersement
     * @return ApproVersements
     */
    public function setMVersement($mVersement)
    {
        $this->mVersement = $mVersement;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMApproVersement()
    {
        return $this->mApproVersement;
    }

    /**
     * @param mixed $mApproVersement
     * @return ApproVersements
     */
    public function setMApproVersement($mApproVersement)
    {
        $this->mApproVersement = $mApproVersement;
        return $this;
    }

    

  /*public function getMApproVersement(){
      return $this->mVersement+$this->mAppro;
  }*/
    /**
     * @return mixed
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param mixed $statut
     * @return ApproVersements
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJourneeCaissePartenaire()
    {
        return $this->journeeCaissePartenaire;
    }

    /**
     * @param mixed $journeeCaissePartenaire
     * @return ApproVersements
     */
    public function setJourneeCaissePartenaire($journeeCaissePartenaire)
    {
        $this->journeeCaissePartenaire = $journeeCaissePartenaire;
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
     * @return ApproVersements
     */
    public function setMSaisie($mSaisie)
    {
        $this->mSaisie = $mSaisie;
        return $this;
    }

}