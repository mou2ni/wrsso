<?php
/**
 * Created by PhpStorm.
 * User: Mouni
 * Date: 22/11/2016
 * Time: 10:39
 */

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity (repositoryClass="App\Repository\DeviseJourneesRepository")
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
     * @ORM\OneToOne(targetEntity="App\Entity\DeviseJournees")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $deviseJourneePrecedente;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DeviseMouvements", mappedBy="deviseJournee", cascade={"persist"})
     */
    private $deviseMouvements;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses" , inversedBy="deviseJournees", cascade={"persist"})
     * @ORM\JoinColumn(name="idJourneeCaisse", referencedColumnName="id", nullable=true)
     */
    private $journeeCaisse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devises", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $devise;

    /*
     * @ORM\OneToOne(targetEntity="App\Entity\Billetages", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $billetOuv;

    /**
     * @ORM\Column(type="integer")
     */
    private $qteOuv=0;

    /**
     * @ORM\Column(type="string")
     */
    private $detailLiquiditeOuv='';


    /**
     * @ORM\Column(type="integer")
     */
    private $qteAchat=0;

    /**
     * @ORM\Column(type="integer")
     */
    private $qteVente=0;

 
    /**
     * @ORM\Column(type="float")
     */
    private $mCvdAchat=0;

    /**
     * @ORM\Column(type="float")
     */
    private $mCvdVente=0;

    /**
     * @ORM\Column(type="integer")
     */
    private $qteIntercaisse=0;

    /*
     * @ORM\OneToOne(targetEntity="App\Entity\Billetages", cascade={"persist"} )
     * @ORM\JoinColumn(nullable=true)
     */
    private $billetFerm;

    /**
     * @ORM\Column(type="integer")
     */
    private $qteFerm=0;

    /**
     * @ORM\Column(type="string")
     */
    private $detailLiquiditeFerm='';


    /**
     * DeviseJournees constructor.
     * @param $journeeCaisse
     * @param $devise
     */
    public function __construct($journeeCaisse, $devise)
    {
        $this->deviseMouvements = new ArrayCollection();
        $this->journeeCaisse = $journeeCaisse;
        $this->devise = $devise;
        //$this->billetOuv = new Billetages();
        //$this->billetFerm = new Billetages();
    }


    public function updateM($champ,$montant){
        $this->$champ+=$montant;
    }

    public function updateMCvdAchatVente($montant)
    {
        if ($montant>0){
            $this->mCvdVente += $montant;
        }else{
            $this->mCvdAchat += $montant;
        }

        //$this->idJourneeCaisse->updateMCvd($montant);

        return $this;

    }

    public function getQteMouvement(){
        return $this->getQteAchat()+$this->getQteVente()+$this->getQteIntercaisse();
    }

    public function getSolde(){
        return $this->getQteOuv()+$this->getQteMouvement();
    }
    public function updateQteAchatVente($nombre)
    {
        if ($nombre>0) {
            $this->qteAchat += $nombre;
        }else{
            $this->qteVente += $nombre;
        }
        //$this->qteFerm+=$nombre;
        return $this;

    }

    public function updateQteIntercaisse($nombre)
    {
        $this->qteIntercaisse += $nombre;
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
        $qteFermPrecedent=($this->getDeviseJourneePrecedente())?$this->getDeviseJourneePrecedente()->getQteFerm():0;
        return $this->getQteOuv()-$qteFermPrecedent;
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
    public function getQteFerm()
    {
        return $this->qteFerm;
    }
    /**
     * @return this
     */
    public function setQteFerm($qteFerm)
    {
        $this->qteFerm=$qteFerm;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEcartFerm()
    {
        return $this->getQteFerm()-$this->getSolde();
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

    /**
     * @return mixed
     */
    public function getDeviseMouvements()
    {
        return $this->deviseMouvements;
    }

    /**
     * @param mixed $deviseMouvements
     * @return DeviseJournees
     */
    public function setDeviseMouvements($deviseMouvements)
    {
        $this->deviseMouvements = $deviseMouvements;
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

    public function getSoldeCvdMvt(){
        return $this->getMCvdAchat()+$this->getMCvdVente();
    }

    public function getSoldeQteMvt(){
        return $this->getQteAchat()+$this->getQteVente();
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
     * @return DeviseJournees
     */
    public function setJourneeCaisse($journeeCaisse)
    {
        $this->journeeCaisse = $journeeCaisse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDevise()
    {
        return $this->devise;
    }

    /**
     * @param mixed $devise
     * @return DeviseJournees
     */
    public function setDevise($devise)
    {
        $this->devise = $devise;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBilletOuv()
    {
        return $this->billetOuv;
    }

    /**
     * @param mixed $billetOuv
     * @return DeviseJournees
     */
    public function setBilletOuv($billetOuv)
    {
        $this->qteOuv=$billetOuv->getValeurTotal();
        $this->billetOuv = $billetOuv;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBilletFerm()
    {
        return $this->billetFerm;
    }

    /**
     * @param mixed $billetFerm
     * @return DeviseJournees
     */
    public function setBilletFerm($billetFerm)
    {
        $this->billetFerm = $billetFerm;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDetailLiquiditeOuv()
    {
        return $this->detailLiquiditeOuv;
    }


    /**
     * @param mixed $detailLiquiditeOuv
     * @return $this
     */
    public function setDetailLiquiditeOuv($detailLiquiditeOuv)
    {
        $this->detailLiquiditeOuv = $detailLiquiditeOuv;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDetailLiquiditeFerm()
    {
        return $this->detailLiquiditeFerm;
    }


    /**
     * @param mixed $detailLiquiditeFerm
     * @return $this
     */
    public function setDetailLiquiditeFerm($detailLiquiditeFerm)
    {
        $this->detailLiquiditeFerm = $detailLiquiditeFerm;
        return $this;
    }

    /**
     * @return DeviseJournees
     */
    public function getDeviseJourneePrecedente()
    {
        return $this->deviseJourneePrecedente;
    }

    /**
     * @param mixed $deviseJourneePrecedente
     * @return DeviseJournees
     */
    public function setDeviseJourneePrecedente($deviseJourneePrecedente)
    {
        $this->deviseJourneePrecedente = $deviseJourneePrecedente;
        return $this;
    }

}