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
 * @ORM\Entity
 * @ORM\Table(name="ParamComptables")
 */
class ParamComptables
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $codeStructure;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes")
     * @ORM\JoinColumn(name="id_cpt_intercaisse")
     */
    private $compteIntercaisse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes")
     * @ORM\JoinColumn(name="id_cpt_cvd")
     */
    private $compteContreValeurDevise;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes")
     * @ORM\JoinColumn(name="id_cpt_compense")
     */
    private $compteCompense;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes")
     */
    private $compteChargeBaseSalaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes")
     */
    private $compteChargeLogeSalaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes")
     */
    private $compteChargeTranspSalaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes")
     */
    private $compteChargeFonctSalaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes")
     */
    private $compteChargeIndemSalaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes")
     */
    private $compteChargeCotiPatronale;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes")
     */
    private $compteTaxeSalaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes")
     */
    private $compteOrgaSocial;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes")
     */
    private $compteOrgaImpotSalaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes")
     */
    private $compteOrgaTaxeSalaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes")
     */
    private $compteOrgaTaxeFactClt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes")
     */
    private $compteOrgaTaxeFactFseur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes")
     * @ORM\JoinColumn(name="id_cpt_ecart")
     */
    private $compteEcartCaisse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes")
     * @ORM\JoinColumn(name="id_cpt_charges")
     */
    private $compteDiversCharge;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes")
     * @ORM\JoinColumn(name="id_cpt_produits")
     */
    private $compteDiversProduits;

    /**
     * @return mixed
     */
    public function getCodeStructure()
    {
        return $this->codeStructure;
    }

    /**
     * @param mixed $codeStructure
     * @return ParamComptables
     */
    public function setCodeStructure($codeStructure)
    {
        $this->codeStructure = $codeStructure;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompteIntercaisse()
    {
        return $this->compteIntercaisse;
    }

    /**
     * @param mixed $compteIntercaisse
     * @return ParamComptables
     */
    public function setCompteIntercaisse($compteIntercaisse)
    {
        $this->compteIntercaisse = $compteIntercaisse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompteContreValeurDevise()
    {
        return $this->compteContreValeurDevise;
    }

    /**
     * @param mixed $compteContreValeurDevise
     * @return ParamComptables
     */
    public function setCompteContreValeurDevise($compteContreValeurDevise)
    {
        $this->compteContreValeurDevise = $compteContreValeurDevise;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompteCompense()
    {
        return $this->compteCompense;
    }

    /**
     * @param mixed $compteCompense
     * @return ParamComptables
     */
    public function setCompteCompense($compteCompense)
    {
        $this->compteCompense = $compteCompense;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompteEcartCaisse()
    {
        return $this->compteEcartCaisse;
    }

    /**
     * @param mixed $compteEcartCaisse
     * @return ParamComptables
     */
    public function setCompteEcartCaisse($compteEcartCaisse)
    {
        $this->compteEcartCaisse = $compteEcartCaisse;
        return $this;
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
     * @return ParamComptables
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompteDiversCharge()
    {
        return $this->compteDiversCharge;
    }

    /**
     * @param mixed $compteDiversCharge
     * @return ParamComptables
     */
    public function setCompteDiversCharge($compteDiversCharge)
    {
        $this->compteDiversCharge = $compteDiversCharge;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompteDiversProduits()
    {
        return $this->compteDiversProduits;
    }

    /**
     * @param mixed $compteDiversProduits
     * @return ParamComptables
     */
    public function setCompteDiversProduits($compteDiversProduits)
    {
        $this->compteDiversProduits = $compteDiversProduits;
        return $this;
    }

    
}