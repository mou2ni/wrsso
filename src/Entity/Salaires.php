<?php
/**
 * Created by Hamado.
 * Date: 21/01/2019
 * Time: 07:14
 */

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SalairesRepository")
 * @ORM\Table(name="Salaires")
 */
class Salaires
{
    const STAT_INITIAL='I', STAT_POSITIONNE='PO', STAT_PAYE='PA', STAT_COMPTABILISE='C';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transactions")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $transaction;

    /**
     * @ORM\Column(type="date", unique=true))
     */
    private $periode;

    /**
     * @ORM\Column(type="date"))
     */
    private $dateSalaire;

    /**
     * @ORM\Column(type="float")
     */
    private $mBrutTotal;

    /**
     * @ORM\Column(type="float")
     */
    private $mTaxeTotal;

    /**
     * @ORM\Column(type="float")
     */
    private $mImpotTotal;

    /**
     * @ORM\Column(type="float")
     */
    private $mSecuriteSocialSalarie;

    /**
     * @ORM\Column(type="float")
     */
    private $mSecuriteSocialPatronal;

    /**
     * @ORM\Column(type="string")
     */
    private $statut=Salaires::STAT_INITIAL;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LigneSalaires", mappedBy="salaire", cascade={"persist"})
     */
    private $ligneSalaires;

    /**s
     * @ORM\OneToMany(targetEntity="App\Entity\Collaborateurs", mappedBy="dernierSalaire", cascade={"persist"})
     */
    private $collaborateurs;

    public function __construct()
    {
        $this->ligneSalaires=new ArrayCollection();
        $this->dateSalaire=new \DateTime();
        $this->periode=new \DateTime();
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
     * @return Salaires
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMBrutTotal()
    {
        return $this->mBrutTotal;
    }

    /**
     * @param mixed $mBrutTotal
     * @return Salaires
     */
    public function setMBrutTotal($mBrutTotal)
    {
        $this->mBrutTotal = $mBrutTotal;
        return $this;
    }

    public function getPeriodeSalaire(){
        return $this->getPeriode()->format('m-Y');
    }


    /**
     * @return mixed
     */
    public function getMTaxeTotal()
    {
        return $this->mTaxeTotal;
    }

    /**
     * @param mixed $mTaxeTotal
     * @return Salaires
     */
    public function setMTaxeTotal($mTaxeTotal)
    {
        $this->mTaxeTotal = $mTaxeTotal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMImpotTotal()
    {
        return $this->mImpotTotal;
    }

    /**
     * @param mixed $mImpotTotal
     * @return Salaires
     */
    public function setMImpotTotal($mImpotTotal)
    {
        $this->mImpotTotal = $mImpotTotal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMSecuriteSocialSalarie()
    {
        return $this->mSecuriteSocialSalarie;
    }

    /**
     * @param mixed $mSecuriteSocialSalarie
     * @return Salaires
     */
    public function setMSecuriteSocialSalarie($mSecuriteSocialSalarie)
    {
        $this->mSecuriteSocialSalarie = $mSecuriteSocialSalarie;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMSecuriteSocialPatronal()
    {
        return $this->mSecuriteSocialPatronal;
    }

    /**
     * @param mixed $mSecuriteSocialPatronal
     * @return Salaires
     */
    public function setMSecuriteSocialPatronal($mSecuriteSocialPatronal)
    {
        $this->mSecuriteSocialPatronal = $mSecuriteSocialPatronal;
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
     * @return Salaires
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
        return $this;
    }

    /**
     * @param LigneSalaires $ligneSalaire
     * @return $this
     */
    public function addLigneSalaire(LigneSalaires $ligneSalaire)
    {
        $ligneSalaire->setCompteRemunerationDue($ligneSalaire->getCollaborateur()->getCompteRemunerationDue());
        $ligneSalaire->setCompteVirement($ligneSalaire->getCollaborateur()->getCompteVirement())
            ->setAgence($ligneSalaire->getCollaborateur()->getAgence());
        $this->ligneSalaires->add($ligneSalaire);
        $ligneSalaire->setSalaire($this);
        return $this;
    }

    /**
     * @param LigneSalaires $ligneSalaire
     * @return $this
     */
    public function removeLigneSalaire(LigneSalaires $ligneSalaire)
    {
        $this->ligneSalaires->removeElement($ligneSalaire);

        $this->mBrutTotal-=$ligneSalaire->getMBrutTotal();
        $this->mImpotTotal-=$ligneSalaire->getMImpotSalarie();
        $this->mTaxeTotal-=$ligneSalaire->getMTaxePatronale();
        $this->mSecuriteSocialSalarie-=$ligneSalaire->getMSecuriteSocialeSalarie();
        $this->mSecuriteSocialPatronal-=$ligneSalaire->getMSecuriteSocialePatronal();
        return $this;
    }

    public function fillLigneSalaireFromCollaborateurs($collaborateurs){
        foreach ($collaborateurs as $collaborateur){
            $ligneSalaire=new LigneSalaires();
            $ligneSalaire->setCollaborateur($collaborateur);
            $ligneSalaire->fillDataFromCollaborateur();
            $this->addLigneSalaire($ligneSalaire);

            $this->mBrutTotal+=$ligneSalaire->getMBrutTotal();
            $this->mImpotTotal+=$ligneSalaire->getMImpotSalarie();
            $this->mTaxeTotal+=$ligneSalaire->getMTaxePatronale();
            $this->mSecuriteSocialSalarie+=$ligneSalaire->getMSecuriteSocialeSalarie();
            $this->mSecuriteSocialPatronal+=$ligneSalaire->getMSecuriteSocialePatronal();
        }

    }


    /**
     * @return LigneSalaires
     */
    public function getLigneSalaires()
    {
        return $this->ligneSalaires;
    }

    /**
     * @param LigneSalaires $ligneSalaires
     * @return Salaires
     */
    public function setLigneSalaires($ligneSalaires)
    {
        $this->ligneSalaires = $ligneSalaires;
        return $this;
    }

    /**
     * @return Collaborateurs
     */
    public function getCollaborateurs()
    {
        return $this->collaborateurs;
    }

    /**
     * @param Collaborateurs $collaborateurs
     * @return Salaires
     */
    public function setCollaborateurs($collaborateurs)
    {
        $this->collaborateurs = $collaborateurs;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateSalaire()
    {
        return $this->dateSalaire;
    }

    /**
     * @param mixed $dateSalaire
     * @return Salaires
     */
    public function setDateSalaire($dateSalaire)
    {
        $this->dateSalaire = $dateSalaire;
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
     * @return Salaires
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
        return $this;
    }

    public function maintenirTotaux(){
        $mBrutTotal=0;
        $mTaxeTotal=0;
        $mImpotTotal=0;
        $mSecuriteSocialSalarie=0;
        $mSecuriteSocialPatronal=0;

        foreach ($this->getLigneSalaires() as $ligneSalaire){
            $mBrutTotal+=$ligneSalaire->getMBrutTotal();
            $mTaxeTotal+=$ligneSalaire->getMTaxePatronale();
            $mImpotTotal+=$ligneSalaire->getMImpotSalarie();
            $mSecuriteSocialSalarie+=$ligneSalaire->getMSecuriteSocialeSalarie();
            $mSecuriteSocialPatronal+=$ligneSalaire->getMSecuriteSocialePatronal();
        }

        $this->setMBrutTotal($mBrutTotal);
        $this->setMTaxeTotal($mTaxeTotal);
        $this->setMImpotTotal($mImpotTotal);
        $this->setMSecuriteSocialSalarie($mSecuriteSocialSalarie);
        $this->setMSecuriteSocialPatronal($mSecuriteSocialPatronal);

        return $this;
    }

    public function getCoutTotal(){
        return $this->getMBrutTotal()+$this->getMSecuriteSocialPatronal()+$this->getMTaxeTotal();
    }

    public function getNetTotal(){
        return $this->getMBrutTotal()-$this->getMImpotTotal()-$this->getMSecuriteSocialSalarie();
    }

    /**
     * @return \DateTime
     */
    public function getPeriode()
    {
        return $this->periode;
    }

    /**
     * @param mixed $periode
     * @return Salaires
     */
    public function setPeriode($periode)
    {
        $this->periode = $periode;
        return $this;
    }


}