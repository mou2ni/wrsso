<?php
/**
 * Created by PhpStorm.
 * User: Mouni
 * Date: 22/11/2016
 * Time: 10:39
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="DetteCreditDivers")
 */
class DetteCreditDivers
{
    const REMB='R',INIT='I';
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
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses", inversedBy="detteCredits")
     * @ORM\JoinColumn(name="journeeCaissesCreation", referencedColumnName="id",nullable=false)
     */
    private $journeeCaisse;

    /*
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses", inversedBy="detteCreditRembs")
     * @ORM\JoinColumn(name="journeeCaisseRemb", referencedColumnName="id",nullable=false)

    private $journeeCaissesRemb;
*/
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
     * DetteCreditDivers constructor.
     * @param $caisse
     * @param $journeeCaisse
     * @param $dateDC
     * @param $statut
     * @param $mCredit
     * @param $mDette
     */
    public function __construct(JourneeCaisses $journeeCaisse)
    {
        $this->journeeCaisse = $journeeCaisse;
        $this->caisse = $journeeCaisse->getCaisse();
        $this->dateDC = new \DateTime('now');
        $this->statut = $this::INIT;
        $this->mCredit = 0;
        $this->mDette = 0;
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
    public function getJourneeCaisse()
    {
        return $this->journeeCaisse;
    }

    /**
     * @param mixed $journeeCaisse
     * @return DetteCreditDivers
     */
    public function setJourneeCaisse($journeeCaisse)
    {
        $this->journeeCaisse = $journeeCaisse;
        return $this;
    }


}