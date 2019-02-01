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
     * @ORM\ManyToOne(targetEntity="App\Entity\Transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $transaction;

    /**
     * @ORM\Column(type="date", unique=true))
     */
    private $periodeSalaire;

    /**
     * @ORM\Column(type="float")
     */
    private $mNetTotal;

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
    private $mSecuriteSocialTotal;

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
    public function getMNetTotal()
    {
        return $this->mNetTotal;
    }

    /**
     * @param mixed $mNetTotal
     * @return Salaires
     */
    public function setMNetTotal($mNetTotal)
    {
        $this->mNetTotal = $mNetTotal;
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
    public function getMSecuriteSocialTotal()
    {
        return $this->mSecuriteSocialTotal;
    }

    /**
     * @param mixed $mSecuriteSocialTotal
     * @return Salaires
     */
    public function setMSecuriteSocialTotal($mSecuriteSocialTotal)
    {
        $this->mSecuriteSocialTotal = $mSecuriteSocialTotal;
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

    public function fillLigneSalaireFromCollaborateurs(ArrayCollection $collaborateurs){
        foreach ($collaborateurs as $collaborateur){
            $lignSalaire=new LigneSalaires();
            $lignSalaire->setCollaborateur();
            $lignSalaire->fillDataFromCollaborateur();
            $this->addLigneSalaire($lignSalaire);
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

}