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
    public function getCompteIntercaisse()
    {
        return $this->CompteIntercaisse;
    }

    /**
     * @param mixed $CompteIntercaisse
     */
    public function setCompteIntercaisse($CompteIntercaisse)
    {
        $this->CompteIntercaisse = $CompteIntercaisse;
    }

    /**
     * @return mixed
     */
    public function getCompteContreValeurDevise()
    {
        return $this->CompteContreValeurDevise;
    }

    /**
     * @param mixed $CompteContreValeurDevise
     */
    public function setCompteContreValeurDevise($CompteContreValeurDevise)
    {
        $this->CompteContreValeurDevise = $CompteContreValeurDevise;
    }

    /**
     * @return mixed
     */
    public function getCompteCompense()
    {
        return $this->CompteCompense;
    }

    /**
     * @param mixed $CompteCompense
     */
    public function setCompteCompense($CompteCompense)
    {
        $this->CompteCompense = $CompteCompense;
    }

    /**
     * @return mixed
     */
    public function getCompteChargeSalaireNet()
    {
        return $this->CompteChargeSalaireNet;
    }

    /**
     * @param mixed $CompteChargeSalaireNet
     */
    public function setCompteChargeSalaireNet($CompteChargeSalaireNet)
    {
        $this->CompteChargeSalaireNet = $CompteChargeSalaireNet;
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