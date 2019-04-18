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
    const STAT_COMPTA='C', STAT_INITIAL='I',STAT_VALIDE='V',STAT_VALIDATION_AUTO='VA', STAT_ANNULE='X', RECETTE=7, DEPENSE=6;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Agences", inversedBy="recetteDepenses", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $agence;

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
     * @ORM\JoinColumn(nullable=true)
     */
    private $journeeCaisse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $compteGestion;

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
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $numDocumentCompta;

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

    //private $estCharge=false;
    //private $estProduit=false;

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
     * @return Utilisateurs
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
     * @return Transactions
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
     * @return JourneeCaisses
     */
    public function getJourneeCaisse()
    {
        return $this->journeeCaisse;
    }

    /**
     * @param JourneeCaisses $journeeCaisse
     * @return RecetteDepenses
     */
    public function setJourneeCaisse(JourneeCaisses $journeeCaisse)
    {
        $this->setCompteTier($journeeCaisse->getCaisse()->getCompteOperation());
        $this->journeeCaisse = $journeeCaisse;
        return $this;
    }

    /**
     * @return \DateTime
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
        //if($mRecette<0) $mRecette=abs($mRecette);
        $this->mRecette = abs($mRecette);
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
        $this->mDepense = abs($mDepense);
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
        //empecher la modification si déjà comptabilisé
        if ($this->getStatut()==RecetteDepenses::STAT_COMPTA) return $this;
        //if($mSaisie<0) $mSaisie=abs($mSaisie);
        /*$compte=$this->getTypeOperationComptable()->getCompte();
        $classCompte=substr($compte,0,1);

        if($classCompte==RecetteDepenses::DEPENSE) $this->setMDepense($mSaisie);
        if($classCompte==RecetteDepenses::RECETTE) $this->setMRecette($mSaisie);*/
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
     * @param TypeOperationComptables $typeOperationComptable
     * @return RecetteDepenses
     */
    public function setTypeOperationComptable(TypeOperationComptables $typeOperationComptable)
    {
        $this->setCompteGestion($typeOperationComptable->getCompte());
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
     * @return mixed
     */
    public function getCompteGestion()
    {
        return $this->compteGestion;
    }

    /**
     * @param mixed $compteGestion
     * @return RecetteDepenses
     */
    public function setCompteGestion($compteGestion)
    {
        $this->compteGestion = $compteGestion;
        return $this;
    }


    /*
     * @return boolean

    public function isEstCharge()
    {
        return $this->estCharge;
    }*/

    /*
     * @param boolean $estCharge
     * @return RecetteDepenses

    public function setEstCharge($estCharge)
    {
        $this->estCharge = $estCharge;
        return $this;
    }*/

    /*
     * @return boolean

    public function isEstProduit()
    {
        return $this->estProduit;
    }
*/
    /*
     * @param boolean $estProduit
     * @return RecetteDepenses

    public function setEstProduit($estProduit)
    {
        $this->estProduit = $estProduit;
        return $this;
    }*/

    public function typageCompteGestion(){
        $classCompte=substr($this->getCompteGestion(),0,1);
        if ($classCompte==RecetteDepenses::DEPENSE) {
            $this->setMDepense($this->getMSaisie());
            $this->setMRecette(0);
        }elseif ($classCompte==RecetteDepenses::RECETTE) {
            $this->setMRecette($this->getMSaisie());
            $this->setMDepense(0);
        }else return false;
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

    public function comptabiliserNouveau(GenererCompta $genCompta, JourneeCaisses $journeeCaisse){
        if ($this->getEstComptant()) {
            $ok= $genCompta->genComptaRecetteDepenseComptant($this,$journeeCaisse);
            if (!$ok) return false;
            //return $genCompta;
        }else{
            $genCompta->setErrMessage('Recette depenses non comptant non implementé');
            return false;
        }
        $this->setTransaction($genCompta->getTransactions()[0]);
        $this->setStatut(RecetteDepenses::STAT_COMPTA);
        return true;
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
     * @return RecetteDepenses
     */
    public function setUtilisateurValidateur($utilisateurValidateur)
    {
        $this->utilisateurValidateur = $utilisateurValidateur;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumDocumentCompta()
    {
        return $this->numDocumentCompta;
    }

    /**
     * @param mixed $numDocumentCompta
     * @return RecetteDepenses
     */
    public function setNumDocumentCompta($numDocumentCompta)
    {
        $this->numDocumentCompta = $numDocumentCompta;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAgence()
    {
        return $this->agence;
    }

    /**
     * @param mixed $agence
     * @return RecetteDepenses
     */
    public function setAgence($agence)
    {
        $this->agence = $agence;
        return $this;
    }
    
}