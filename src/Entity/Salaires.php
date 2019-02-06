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
    const STAT_INITIAL='IN', STAT_POSITIONNE='PO', STAT_PAYE='PA';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true))
     */
    private $periodeSalaire;

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

    /**
     * @return mixed
     */
    public function getPeriodeSalaire()
    {
        return $this->periodeSalaire;
    }

    /**
     * @param mixed $periodeSalaire
     * @return Salaires
     */
    public function setPeriodeSalaire($periodeSalaire)
    {
        $this->periodeSalaire = $periodeSalaire;
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
        $this->ligneSalaires->add($ligneSalaire);
        return $this;
    }

    /**
     * @param LigneSalaires $ligneSalaire
     * @return $this
     */
    public function removeLigneSalaire(LigneSalaires $ligneSalaire)
    {
        $this->ligneSalaires->removeElement($ligneSalaire);
        return $this;
    }

    public function fillLigneSalaireFromCollaborateurs($collaborateurs){
        foreach ($collaborateurs as $collaborateur){
            $lignSalaire=new LigneSalaires();
            $lignSalaire->setCollaborateur($collaborateur);
            $lignSalaire->fillDataFromCollaborateur();
            $this->addLigneSalaire($lignSalaire);

            $this->mBrutTotal+=$lignSalaire->getMBrutTotal();
            $this->mImpotTotal+=$lignSalaire->getMImpotSalarie();
            $this->mSecuriteSocialSalarie+=$lignSalaire->getMSecuriteSocialeSalarie();
            $this->mSecuriteSocialPatronal+=$lignSalaire->getMSecuriteSocialePatronal();
            $this->mTaxeTotal+=$lignSalaire->getMTaxePatronale();
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

}