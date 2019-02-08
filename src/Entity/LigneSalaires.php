<?php
/**
 * Created by Hamado.
 * Date: 21/01/2019
 * Time: 07:14
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class LigneSalaires
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transactions")
     * @ORM\JoinColumn(nullable=true)
     */
    private $transaction;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Salaires", inversedBy="ligneSalaires", cascade={"persist"})
     */
    private $salaire;

    /**
     * @ORM\Column(type="float")
     */
    private $mSalaireBase;

    /**
     * @ORM\Column(type="float")
     */
    private $mIndemLogement;

    /**
     * @ORM\Column(type="float")
     */
    private $mIndemTransport;

    /**
     * @ORM\Column(type="float")
     */
    private $mIndemFonction;

    /**
     * @ORM\Column(type="float")
     */
    private $mIndemAutres;

    /**
     * @ORM\Column(type="float")
     */
    private $mHeureSup;

    /**
     * @ORM\Column(type="float")
     */
    private $mSecuriteSocialeSalarie;

    /**
     * @ORM\Column(type="float")
     */
    private $mSecuriteSocialePatronal;

    /**
     * @ORM\Column(type="float")
     */
    private $mImpotSalarie;

    /**
     * @ORM\Column(type="float")
     */
    private $mTaxePatronale;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes", cascade={"persist"})
     */
    private $compteVirement;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes", cascade={"persist"})
     */
    private $compteRemunerationDue;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Collaborateurs", cascade={"persist"})
     */
    private $collaborateur;



    public function getMBrutTotal(){
        return $this->getMSalaireBase()
            +$this->getMHeureSup()
            +$this->getMIndemLogement()
            +$this->getMIndemTransport()
            +$this->getMIndemFonction()
            +$this->getMIndemAutres()
            ;
    }


    public function getMNet(){
        return $this->getMBrutTotal()
            -$this->getMSecuriteSocialeSalarie()
            -$this->getMImpotSalarie();
    }

    public function getMChargeTotal(){
        return $this->getMBrutTotal()
            +$this->getMTaxePatronale()
            +$this->getMSecuriteSocialePatronal();
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
     * @return LigneSalaires
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSalaire()
    {
        return $this->salaire;
    }

    /**
     * @param mixed $salaire
     * @return LigneSalaires
     */
    public function setSalaire($salaire)
    {
        $this->salaire = $salaire;
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
     * @return LigneSalaires
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
     * @return LigneSalaires
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
     * @return LigneSalaires
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
     * @return LigneSalaires
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
     * @return LigneSalaires
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
     * @return LigneSalaires
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
     * @return LigneSalaires
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
     * @param mixed $mSecuriteSocialePatronale
     * @return LigneSalaires
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
     * @return LigneSalaires
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
     * @return LigneSalaires
     */
    public function setMTaxePatronale($mTaxePatronale)
    {
        $this->mTaxePatronale = $mTaxePatronale;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompteVirement()
    {
        return $this->compteVirement;
    }

    /**
     * @param mixed $compteVirement
     * @return LigneSalaires
     */
    public function setCompteVirement($compteVirement)
    {
        $this->compteVirement = $compteVirement;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompteRemunerationDue()
    {
        return $this->compteRemunerationDue;
    }

    /**
     * @param mixed $compteRemunerationDue
     * @return LigneSalaires
     */
    public function setCompteRemunerationDue($compteRemunerationDue)
    {
        $this->compteRemunerationDue = $compteRemunerationDue;
        return $this;
    }

    /**
     * @return Collaborateurs
     */
    public function getCollaborateur()
    {
        return $this->collaborateur;
    }

    /**
     * @param Collaborateurs $collaborateur
     * @return LigneSalaires
     */
    public function setCollaborateur($collaborateur)
    {
        $this->collaborateur = $collaborateur;
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
     * @return LigneSalaires
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
        return $this;
    }

    public function fillDataFromCollaborateur(){
        $this->setCompteVirement($this->getCollaborateur()->getCompteVirement())
            ->setMSalaireBase($this->getCollaborateur()->getMSalaireBase())
            ->setMIndemTransport($this->getCollaborateur()->getMIndemTransport())
            ->setMIndemFonction($this->getCollaborateur()->getMIndemFonction())
            ->setMIndemLogement($this->getCollaborateur()->getMIndemLogement())
            ->setMIndemAutres($this->getCollaborateur()->getMIndemAutres())
            ->setMSecuriteSocialeSalarie($this->getCollaborateur()->getMSecuriteSocialeSalarie())
            ->setMSecuriteSocialePatronal($this->getCollaborateur()->getMSecuriteSocialePatronal())
            ->setMImpotSalarie($this->getCollaborateur()->getMImpotSalarie())
            ->setMTaxePatronale($this->getCollaborateur()->getMTaxePatronale())
            ->setMHeureSup($this->getCollaborateur()->getMHeureSup())
        ;

        return $this;
    }

    

}