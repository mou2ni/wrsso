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
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idJourneeCaisse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devises")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idDevise;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Billets")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Billets")
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
    public function getIdDevise()
    {
        return $this->idDevise;
    }

    /**
     * @param mixed $idDevise
     */
    public function setIdDevise($idDevise)
    {
        $this->idDevise = $idDevise;
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
     */
    public function setIdBilletOuv($idBilletOuv)
    {
        $this->idBilletOuv = $idBilletOuv;
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
     */
    public function setQteOuv($qteOuv)
    {
        $this->qteOuv = $qteOuv;
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
     */
    public function setEcartOuv($ecartOuv)
    {
        $this->ecartOuv = $ecartOuv;
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
     */
    public function setQteAchat($qteAchat)
    {
        $this->qteAchat = $qteAchat;
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
     */
    public function setQteVente($qteVente)
    {
        $this->qteVente = $qteVente;
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
     */
    public function setMCvdAchat($mCvdAchat)
    {
        $this->mCvdAchat = $mCvdAchat;
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
     */
    public function setMCvdVente($mCvdVente)
    {
        $this->mCvdVente = $mCvdVente;
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
     */
    public function setQteIntercaisse($qteIntercaisse)
    {
        $this->qteIntercaisse = $qteIntercaisse;
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
     */
    public function setIdBilletFerm($idBilletFerm)
    {
        $this->idBilletFerm = $idBilletFerm;
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
     */
    public function setQteFerm($qteFerm)
    {
        $this->qteFerm = $qteFerm;
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
     */
    public function setEcartFerm($ecartFerm)
    {
        $this->ecartFerm = $ecartFerm;
    }


}