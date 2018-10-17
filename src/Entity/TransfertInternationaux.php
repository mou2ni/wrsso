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
 * @ORM\Table(name="TransfertInternationaux")
 */
class TransfertInternationaux
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses" , inversedBy="transfertInternationaux")
     * @ORM\JoinColumn(name="idJourneeCaisse", referencedColumnName="id", nullable=false)
     */
    private $idJourneeCaisse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SystemTransfert")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idSystemTransfert;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pays")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idPays;

    /**
     * @ORM\Column(type="string")
     */
    private $sens;

    /**
     * @ORM\Column(type="float")
     */
    private $mTransfert=0;

    /**
     * @ORM\Column(type="float")
     */
    private $mFraisHt=0;

    /**
     * @ORM\Column(type="float")
     */
    private $mTva=0;

    /**
     * @ORM\Column(type="float")
     */
    private $mAutresTaxes=0;

    /**
     * @ORM\Column(type="float")
     */
    private $mTransfertTTC=0;


    public function __toString()
    {
        return ''.$this->getSens().' '.$this->getIdSystemTransfert().' '.$this->getMTransfert();
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
    public function getIdJourneeCaisse()
    {
        return $this->idJourneeCaisse;
    }

    /**
     * @param mixed $idJourneeCaisse
     */
    public function setIdJourneeCaisse($idJourneeCaisse)
    {
        $this->idJourneeCaisse = $idJourneeCaisse;
    }

    /**
     * @return mixed
     */
    public function getIdSystemTransfert()
    {
        return $this->idSystemTransfert;
    }

    /**
     * @param mixed $idSystemTransfert
     */
    public function setIdSystemTransfert($idSystemTransfert)
    {
        $this->idSystemTransfert = $idSystemTransfert;
    }

    /**
     * @return mixed
     */
    public function getIdPays()
    {
        return $this->idPays;
    }

    /**
     * @param mixed $idPays
     */
    public function setIdPays($idPays)
    {
        $this->idPays = $idPays;
    }

    /**
     * @return mixed
     */
    public function getSens()
    {
        return $this->sens;
    }

    /**
     * @param mixed $sens
     */
    public function setSens($sens)
    {
        $this->sens = $sens;
    }

    /**
     * @return mixed
     */
    public function getMTransfert()
    {
        return $this->mTransfert;
    }

    /**
     * @param mixed $mTransfert
     */
    public function setMTransfert($mTransfert)
    {
        $this->mTransfert = $mTransfert;
    }

    /**
     * @return mixed
     */
    public function getMFraisHt()
    {
        return $this->mFraisHt;
    }

    /**
     * @param mixed $mFraisHt
     */
    public function setMFraisHt($mFraisHt)
    {
        $this->mFraisHt = $mFraisHt;
    }

    /**
     * @return mixed
     */
    public function getMTva()
    {
        return $this->mTva;
    }

    /**
     * @param mixed $mTva
     */
    public function setMTva($mTva)
    {
        $this->mTva = $mTva;
    }

    /**
     * @return mixed
     */
    public function getMAutresTaxes()
    {
        return $this->mAutresTaxes;
    }

    /**
     * @param mixed $mAutresTaxes
     */
    public function setMAutresTaxes($mAutresTaxes)
    {
        $this->mAutresTaxes = $mAutresTaxes;
    }

    /**
     * @return mixed
     */
    public function getMTransfertTTC()
    {
        return $this->mTransfertTTC;
    }

    /**
     * @param mixed $mTransfertTTC
     * @return TransfertInternationaux
     */
    public function setMTransfertTTC($mTransfertTTC)
    {
        $this->mTransfertTTC = $mTransfertTTC;
        return $this;
    }


   }