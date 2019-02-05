<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 30/01/2019
 * Time: 12:03
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CollaborateursRepository")
 */
class Collaborateurs
{
    const STAT_STAGIAIRE='ST', STAT_SALARIE='SA', STAT_PRESTATAIRE='PR', STAT_SORTI='SO';
    const CAT_BFCADRE='BFCA', CAT_BFNONCADRE='BFNCA';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $nom;

    /**
     * @ORM\Column(type="string")
     */
    private $prenom;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateNaissance;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateEntree;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateSortie;

    /**
     * @ORM\Column(type="string")
     */
    private $statut=Collaborateurs::STAT_SALARIE;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbEnfant=0;

    /**
     * @ORM\Column(type="string")
     */
    private $categorie=Collaborateurs::CAT_BFNONCADRE;

    /**
     * @ORM\Column(type="string")
     */
    private $nSecuriteSociale=0;

    /**
     * @ORM\Column(type="float")
     */
    private $mSalaireBase=0;

    /**
     * @ORM\Column(type="float")
     */
    private $mIndemLogement=0;

    /**
     * @ORM\Column(type="float")
     */
    private $mIndemTransport=0;

    /**
     * @ORM\Column(type="float")
     */
    private $mIndemFonction=0;

    /**
     * @ORM\Column(type="float")
     */
    private $mIndemAutres=0;

    /**
     * @ORM\Column(type="float")
     */
    private $mHeureSup=0;

    /**
     * @ORM\Column(type="float")
     */
    private $mSecuriteSocialeSalarie=0;

    /**
     * @ORM\Column(type="float")
     */
    private $mSecuriteSocialePatronal=0;

    /**
     * @ORM\Column(type="float")
     */
    private $mImpotSalarie=0;

    /**
     * @ORM\Column(type="float")
     */
    private $mTaxePatronale=0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes", cascade={"persist"})
     */
    private $compteVirement;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entreprises", cascade={"persist"})
     */
    private $entreprise;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Salaires", cascade={"persist"})
     */
    private $dernierSalaire;


    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getPrenom().' '.$this->getNom();
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
     * @return Collaborateurs
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     * @return Collaborateurs
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     * @return Collaborateurs
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * @param mixed $dateNaissance
     * @return Collaborateurs
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateEntree()
    {
        return $this->dateEntree;
    }

    /**
     * @param mixed $dateEntree
     * @return Collaborateurs
     */
    public function setDateEntree($dateEntree)
    {
        $this->dateEntree = $dateEntree;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateSortie()
    {
        return $this->dateSortie;
    }

    /**
     * @param mixed $dateSortie
     * @return Collaborateurs
     */
    public function setDateSortie($dateSortie)
    {
        $this->dateSortie = $dateSortie;
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
     * @return Collaborateurs
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNbEnfant()
    {
        return $this->nbEnfant;
    }

    /**
     * @param mixed $nbEnfant
     * @return Collaborateurs
     */
    public function setNbEnfant($nbEnfant)
    {
        $this->nbEnfant = $nbEnfant;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param mixed $categorie
     * @return Collaborateurs
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNSecuriteSociale()
    {
        return $this->nSecuriteSociale;
    }

    /**
     * @param mixed $nSecuriteSociale
     * @return Collaborateurs
     */
    public function setNSecuriteSociale($nSecuriteSociale)
    {
        $this->nSecuriteSociale = $nSecuriteSociale;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMSalaireBase()
    {
        return $this->mSalaireBase;
    }

    /**
     * @param mixed $mSalaireBase
     * @return Collaborateurs
     */
    public function setMSalaireBase($mSalaireBase)
    {
        $this->mSalaireBase = $mSalaireBase;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMIndemLogement()
    {
        return $this->mIndemLogement;
    }

    /**
     * @param mixed $mIndemLogement
     * @return Collaborateurs
     */
    public function setMIndemLogement($mIndemLogement)
    {
        $this->mIndemLogement = $mIndemLogement;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMIndemTransport()
    {
        return $this->mIndemTransport;
    }

    /**
     * @param mixed $mIndemTransport
     * @return Collaborateurs
     */
    public function setMIndemTransport($mIndemTransport)
    {
        $this->mIndemTransport = $mIndemTransport;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMIndemFonction()
    {
        return $this->mIndemFonction;
    }

    /**
     * @param mixed $mIndemFonction
     * @return Collaborateurs
     */
    public function setMIndemFonction($mIndemFonction)
    {
        $this->mIndemFonction = $mIndemFonction;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMIndemAutres()
    {
        return $this->mIndemAutres;
    }

    /**
     * @param mixed $mIndemAutres
     * @return Collaborateurs
     */
    public function setMIndemAutres($mIndemAutres)
    {
        $this->mIndemAutres = $mIndemAutres;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMHeureSup()
    {
        return $this->mHeureSup;
    }

    /**
     * @param mixed $mHeureSup
     * @return Collaborateurs
     */
    public function setMHeureSup($mHeureSup)
    {
        $this->mHeureSup = $mHeureSup;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMSecuriteSocialeSalarie()
    {
        return $this->mSecuriteSocialeSalarie;
    }

    /**
     * @param mixed $mSecuriteSocialeSalarie
     * @return Collaborateurs
     */
    public function setMSecuriteSocialeSalarie($mSecuriteSocialeSalarie)
    {
        $this->mSecuriteSocialeSalarie = $mSecuriteSocialeSalarie;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMSecuriteSocialePatronal()
    {
        return $this->mSecuriteSocialePatronal;
    }

    /**
     * @param mixed $mSecuriteSocialePatronal
     * @return Collaborateurs
     */
    public function setMSecuriteSocialePatronal($mSecuriteSocialePatronal)
    {
        $this->mSecuriteSocialePatronal = $mSecuriteSocialePatronal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMImpotSalarie()
    {
        return $this->mImpotSalarie;
    }

    /**
     * @param mixed $mImpotSalarie
     * @return Collaborateurs
     */
    public function setMImpotSalarie($mImpotSalarie)
    {
        $this->mImpotSalarie = $mImpotSalarie;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMTaxePatronale()
    {
        return $this->mTaxePatronale;
    }

    /**
     * @param mixed $mTaxePatronale
     * @return Collaborateurs
     */
    public function setMTaxePatronale($mTaxePatronale)
    {
        $this->mTaxePatronale = $mTaxePatronale;
        return $this;
    }

    /**
     * @return Comptes
     */
    public function getCompteVirement()
    {
        return $this->compteVirement;
    }

    /**
     * @param Comptes $compteVirement
     * @return Collaborateurs
     */
    public function setCompteVirement($compteVirement)
    {
        $this->compteVirement = $compteVirement;
        return $this;
    }

    /**
     * @return Entreprises
     */
    public function getEntreprise()
    {
        return $this->entreprise;
    }

    /**
     * @param Entreprises $entreprise
     * @return Collaborateurs
     */
    public function setEntreprise($entreprise)
    {
        $this->entreprise = $entreprise;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDernierSalaire()
    {
        return $this->dernierSalaire;
    }

    /**
     * @param mixed $dernierSalaire
     * @return Collaborateurs
     */
    public function setDernierSalaire($dernierSalaire)
    {
        $this->dernierSalaire = $dernierSalaire;
        return $this;
    }


}