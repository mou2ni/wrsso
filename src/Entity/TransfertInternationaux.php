<?php
/**
 * Created by PhpStorm.
 * User: Mouni
 * Date: 22/11/2016
 * Time: 10:39
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity
 * @ORM\Entity (repositoryClass="App\Repository\TransfertInternationauxRepository")
 * @ORM\Table(name="TransfertInternationaux")
 * @ORM\HasLifecycleCallbacks()
 */
class TransfertInternationaux
{
    const ERR_NEGATIF=1, ERR_ZERO=0;
    private $e ;
    const TVA=0.18;
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
     * @ORM\ManyToOne(targetEntity="App\Entity\SystemTransfert", inversedBy="transfertInternationaux")
     * @ORM\JoinColumn(name="idSystemTransfert", referencedColumnName="id",nullable=false)
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
     * @Assert\GreaterThan(value="0")
     */
    private $mTransfert=0;

    /**
     * @ORM\Column(type="float")
     * @Assert\GreaterThan(value="0")
     */
    private $mFraisHt=0;

    /**
     * @ORM\Column(type="float")
     * @Assert\GreaterThan(value="0")
     */
    private $mTva=0;

    /**
     * @ORM\Column(type="float")
     * @Assert\GreaterThan(value="0")
     */
    private $mAutresTaxes=0;

    /**
     * @ORM\Column(type="float")
     * @Assert\GreaterThan(value="0")
     */
    private $mTransfertTTC=0;

    /**
     * TransfertInternationaux constructor.
     * @param int $mTransfert
     * @param int $mFraisHt
     * @param int $mTva
     * @param int $mAutresTaxes
     * @param int $mTransfertTTC
     */
    public function __construct()
    {
        $this->mTransfert = 0;
        $this->mFraisHt = 0;
        $this->mTva = 0;
        $this->mAutresTaxes = 0;
        $this->mTransfertTTC = 0;
    }


    public function __toString()
    {
        return ''.$this->getSens().' '.$this->getIdSystemTransfert().' '.$this->getMTransfert();
    }

    /**
     * @ORM\PreUpdate
     */
    public function update(){
        //dump($this->getE());die();
        $this->valider();
    }

    /**
     * @ORM\PrePersist
     */
    public function valider(){
        //dump($this);die();
        if ($this->getE()){
            //dump($this->getE());die();
            //throw new Exception("valeur negative");
            return false;

        }
        return true;
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
        if ($mTransfert>=0)
        $this->mTransfert = $mTransfert;
        else $this->setE($this::ERR_NEGATIF, 'mTransfert');
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
        if ($mFraisHt>=0)
        $this->mFraisHt = $mFraisHt;
        else $this->setE($this::ERR_NEGATIF, 'mFraisHt');
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
        $this->mTva = $this->mFraisHt*$this::TVA;
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
        $autresTaxes = $this->mTransfertTTC-$this->mTransfert-$this->mFraisHt-$this->mTva;
        $this->mAutresTaxes = $autresTaxes>0?$autresTaxes:0;
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
        if ($mTransfertTTC>=0)
        $this->mTransfertTTC = $mTransfertTTC;
        else $this->setE($this::ERR_NEGATIF, 'mTransfertTTC');
        return $this;
    }

    /**
     * @return mixed
     */
    public function getE()
    {
        return $this->e;
    }

    /**
     * @param mixed $e
     * @return TransfertInternationaux
     */
    public function setE($e, String $champ)
    {
        $this->e[$champ] = $e;
        return $this;
    }

   }