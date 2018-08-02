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
     * @ORM\OneToMany(targetEntity="App\Entity\DeviseMouvements", mappedBy="deviseJournee", cascade={"persist"})
     */
    private $deviseMouvements;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses" , inversedBy="deviseJournees", cascade={"persist"})
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
     * @ORM\JoinColumn(nullable=true)
     */
    private $idBilletOuv;

    /**
     * @ORM\Column(type="integer")
     */
    private $qteOuv=0;

    /**
     * @ORM\Column(type="float")
     */
    private $ecartOuv=0;

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

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Billetages" )
     * @ORM\JoinColumn(nullable=true)
     */
    private $idBilletFerm;

    /**
     * @ORM\Column(type="integer")
     */
    private $qteFerm=0;

    /**
     * @ORM\Column(type="integer")
     */
    private $ecartFerm=0;



    public function updateMCvdAchatVente($montant)
    {
        if ($montant>0){
            $this->mCvdVente += $montant;
            //$this->idJourneeCaisse->
        }else{
            $this->mCvdAchat += $montant;
        }
        return $this;

    }

    public function updateQteAchatVente($nombre)
    {
        if ($nombre>0) {
            $this->qteAchat += $nombre;
        }else{
            $this->qteVente += $nombre;
        }
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
    public function setIdBilletOuv(Billetages $idBilletOuv)
    {
        $this->qteOuv=$idBilletOuv->getValeurTotal();
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

}