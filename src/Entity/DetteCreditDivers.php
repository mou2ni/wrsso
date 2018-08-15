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
 * @ORM\Table(name="DetteCreditDivers")
 */
class DetteCreditDivers
{
    const REMB='R',INIT='I', PARCIEL='P';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Caisses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $caisse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses", inversedBy="detteCreditCreations")
     * @ORM\JoinColumn(name="journeeCaissesCreation", referencedColumnName="id",nullable=false)
     */
    private $journeeCaissesCreation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses", inversedBy="detteCreditRembs")
     * @ORM\JoinColumn(name="journeeCaisseRemb", referencedColumnName="id",nullable=false)
     */
    private $journeeCaissesRemb;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDC;

    /**
     * @ORM\Column(type="string")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string")
     */
    private $statut;

    /**
     * @ORM\Column(type="float")
     */
    private $mCredit;

    /**
     * @ORM\Column(type="float")
     */
    private $mDette;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDateDC()
    {
        return $this->dateDC;
    }

    /**
     * @param mixed $dateDC
     */
    public function setDateDC($dateDC)
    {
        $this->dateDC = $dateDC;
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
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
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
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

    /**
     * @return mixed
     */
    public function getMCredit()
    {
        return $this->mCredit;
    }

    /**
     * @param mixed $mCredit
     */
    public function setMCredit($mCredit)
    {
        $this->mCredit = $mCredit;
    }

    /**
     * @return mixed
     */
    public function getMDette()
    {
        return $this->mDette;
    }

    /**
     * @param mixed $mDette
     */
    public function setMDette($mDette)
    {
        $this->mDette = $mDette;
    }

    /**
     * @return mixed
     */
    public function getCaisse()
    {
        return $this->caisse;
    }

    /**
     * @param mixed $caisse
     * @return DetteCreditDivers
     */
    public function setCaisse($caisse)
    {
        $this->caisse = $caisse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJourneeCaissesCreation()
    {
        return $this->journeeCaissesCreation;
    }

    /**
     * @param mixed $journeeCaissesCreation
     * @return DetteCreditDivers
     */
    public function setJourneeCaissesCreation($journeeCaissesCreation)
    {
        $this->journeeCaissesCreation = $journeeCaissesCreation;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJourneeCaissesRemb()
    {
        return $this->journeeCaissesRemb;
    }

    /**
     * @param mixed $journeeCaissesRemb
     * @return DetteCreditDivers
     */
    public function setJourneeCaissesRemb($journeeCaissesRemb)
    {
        $this->journeeCaissesRemb = $journeeCaissesRemb;
        return $this;
    }


}