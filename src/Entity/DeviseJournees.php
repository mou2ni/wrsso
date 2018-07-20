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
 * @ORM\Table(name="DeviseJournees")
 */
class DeviseJournees
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses" , inversedBy="DeviseJournees", cascade={"persist"})
     * @ORM\JoinColumn(name="idJourneeCaisse", referencedColumnName="id", nullable=false)
     */
    private $idJourneeCaisse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devises")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idDevise;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Billetages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idBilletOuv;

    /**
     * @ORM\Column(type="float")
     */
    private $qteOuv;

    /**
     * @ORM\Column(type="float")
     */
    private $ecartOuv;

    /**
     * @ORM\Column(type="float")
     */
    private $qteAchat;

    /**
     * @ORM\Column(type="float")
     */
    private $qteVente;

    /**
     * @ORM\Column(type="float")
     */
    private $mCvdAchat;

    /**
     * @ORM\Column(type="float")
     */
    private $mCvdVente;

    /**
     * @ORM\Column(type="float")
     */
    private $qteIntercaisse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Billetages" )
     * @ORM\JoinColumn(nullable=false)
     */
    private $idBilletFerm;

    /**
     * @ORM\Column(type="float")
     */
    private $qteFerm;

    /**
     * @ORM\Column(type="float")
     */
    private $ecartFerm;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return DeviseJournees
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @return DeviseJournees
     */
    public function setIdJourneeCaisse($idJourneeCaisse)
    {
        $this->idJourneeCaisse = $idJourneeCaisse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdDevise()
    {
        return $this->idDevise;
    }

    /**
     * @param mixed $idDevise
     * @return DeviseJournees
     */
    public function setIdDevise($idDevise)
    {
        $this->idDevise = $idDevise;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdBilletOuv()
    {
        return $this->idBilletOuv;
    }

    /**
     * @param mixed $idBilletOuv
     * @return DeviseJournees
     */
    public function setIdBilletOuv($idBilletOuv)
    {
        $this->idBilletOuv = $idBilletOuv;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQteOuv()
    {
        return $this->qteOuv;
    }

    /**
     * @param mixed $qteOuv
     * @return DeviseJournees
     */
    public function setQteOuv($qteOuv)
    {
        $this->qteOuv = $qteOuv;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEcartOuv()
    {
        return $this->ecartOuv;
    }

    /**
     * @param mixed $ecartOuv
     * @return DeviseJournees
     */
    public function setEcartOuv($ecartOuv)
    {
        $this->ecartOuv = $ecartOuv;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQteAchat()
    {
        return $this->qteAchat;
    }

    /**
     * @param mixed $qteAchat
     * @return DeviseJournees
     */
    public function setQteAchat($qteAchat)
    {
        $this->qteAchat = $qteAchat;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQteVente()
    {
        return $this->qteVente;
    }

    /**
     * @param mixed $qteVente
     * @return DeviseJournees
     */
    public function setQteVente($qteVente)
    {
        $this->qteVente = $qteVente;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMCvdAchat()
    {
        return $this->mCvdAchat;
    }

    /**
     * @param mixed $mCvdAchat
     * @return DeviseJournees
     */
    public function setMCvdAchat($mCvdAchat)
    {
        $this->mCvdAchat = $mCvdAchat;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMCvdVente()
    {
        return $this->mCvdVente;
    }

    /**
     * @param mixed $mCvdVente
     * @return DeviseJournees
     */
    public function setMCvdVente($mCvdVente)
    {
        $this->mCvdVente = $mCvdVente;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQteIntercaisse()
    {
        return $this->qteIntercaisse;
    }

    /**
     * @param mixed $qteIntercaisse
     * @return DeviseJournees
     */
    public function setQteIntercaisse($qteIntercaisse)
    {
        $this->qteIntercaisse = $qteIntercaisse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdBilletFerm()
    {
        return $this->idBilletFerm;
    }

    /**
     * @param mixed $idBilletFerm
     * @return DeviseJournees
     */
    public function setIdBilletFerm($idBilletFerm)
    {
        $this->idBilletFerm = $idBilletFerm;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQteFerm()
    {
        return $this->qteFerm;
    }

    /**
     * @param mixed $qteFerm
     * @return DeviseJournees
     */
    public function setQteFerm($qteFerm)
    {
        $this->qteFerm = $qteFerm;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEcartFerm()
    {
        return $this->ecartFerm;
    }

    /**
     * @param mixed $ecartFerm
     * @return DeviseJournees
     */
    public function setEcartFerm($ecartFerm)
    {
        $this->ecartFerm = $ecartFerm;
        return $this;
    }

    public function __toString()
    {
        return ''.$this->getId();
    }


}