<?php
/**
 * Created by Hamado.
 * Date: 21/01/2019
 * Time: 06:39
 */

namespace App\Entity;


use App\Utils\GenererCompta;
//use App\Entity\Transactions;
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
     * @ORM\JoinColumn(nullable=true)
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $compteTier;

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
    private $statut=RecetteDepenses::STAT_INITIAL;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estComptant=true;

    private $mSaisie=0;

    private $estCharge=false;
    private $estProduit=false;

    public function __construct()
    {
        $this->dateOperation = new \DateTime();
    }


    public function comptabiliser($em, JourneeCaisses $journeeCaisse){
        $genCompta=new GenererCompta($em);
        $compte=$this->getTypeOperationComptable()->getCompte();

        $this->typageCompte($compte);

        if (!$compte){
            $genCompta->setErrMessage('Compte non paramétré pour l\'opération comptable '.$this->getTypeOperationComptable()->getLibelle());
            $genCompta->setE(Transactions::ERR_COMPTE_INEXISTANT);
            return $genCompta;
        }
        //dump($recetteDepense);die();
        if($this->estCharge){
            if (!$genCompta->genComptaDepenses($journeeCaisse->getUtilisateur(),$journeeCaisse->getCaisse(),$compte,$this->getLibelle(),$this->getMSaisie(),$journeeCaisse,$this->getDateOperation())){
                return $genCompta;
            };
            $this->setMDepense($this->getMSaisie());
        }elseif($this->estProduit){
            if (!$genCompta->genComptaRecettes($journeeCaisse->getUtilisateur(),$journeeCaisse->getCaisse(),$compte,$this->getLibelle(),$this->getMSaisie(),$journeeCaisse, $this->getDateOperation())){
                return $genCompta;
            };
            $this->setMRecette($this->getMSaisie());
        }else{
            $genCompta->setErrMessage('Le compte numero ['.$compte->getNumCompte().'] parametré dans l\'operation comptable ['.$this->getTypeOperationComptable()->getLibelle().'] n\'est pas un compte de Gestion (classe 6 ou 7).');
            $genCompta->setE(Transactions::ERR_COMPTE_INEXISTANT);
            return $genCompta;
        }

        $this->setTransaction($genCompta->getTransactions()[0]);
        $this->setStatut(RecetteDepenses::STAT_COMPTA);
        return $genCompta;
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
        if($mRecette<0) $mRecette=abs($mRecette);
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
        //if($mDepense<0) $mDepense=abs($mDepense);
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
        //retourne la valeur saisi oubien mdepense ou mrecette le cas échéant
        if ($this->mSaisie!=0) return $this->mSaisie;
        if ($this->getMDepense()) return $this->getMDepense();
        if ($this->getMRecette()) return $this->getMRecette();
        return $this->mSaisie;
    }

    /**
     * @param mixed $mSaisie
     * @return RecetteDepenses
     */
    public function setMSaisie($mSaisie)
    {
        //if($mSaisie<0) $mSaisie=abs($mSaisie);
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

    /**
     * @return boolean
     */
    public function isEstCharge()
    {
        return $this->estCharge;
    }

    /**
     * @param boolean $estCharge
     * @return RecetteDepenses
     */
    public function setEstCharge($estCharge)
    {
        $this->estCharge = $estCharge;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isEstProduit()
    {
        return $this->estProduit;
    }

    /**
     * @param boolean $estProduit
     * @return RecetteDepenses
     */
    public function setEstProduit($estProduit)
    {
        $this->estProduit = $estProduit;
        return $this;
    }

    private function typageCompte($compte){
        $classCompte=substr($compte,0,1);
        $this->estCharge=($classCompte==GenererCompta::COMPTE_CHARGE);
        $this->estProduit=($classCompte==GenererCompta::COMPTE_PRODUIT);
        return true;
    }

    /**
     * @return mixed
     */
    public function getCompteTier()
    {
        return $this->compteTier;
    }

    /**
     * @param mixed $compteTier
     * @return RecetteDepenses
     */
    public function setCompteTier($compteTier)
    {
        $this->compteTier = $compteTier;
        return $this;
    }

}