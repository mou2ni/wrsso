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
     * @ORM\JoinColumn(name="id_cpt_chrg_salaire")
     */
    private $compteChargeSalaireNet;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes")
     * @ORM\JoinColumn(name="id_cpt_ecart")
     */
    private $compteEcartCaisse;

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
    public function getCompteChargeSalaireNet()
    {
        return $this->compteChargeSalaireNet;
    }

    /**
     * @param mixed $compteChargeSalaireNet
     * @return ParamComptables
     */
    public function setCompteChargeSalaireNet($compteChargeSalaireNet)
    {
        $this->compteChargeSalaireNet = $compteChargeSalaireNet;
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




}