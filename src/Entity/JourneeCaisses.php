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
 * @ORM\Entity
 * @ORM\Table(name="JourneeCaisses")
 */
class JourneeCaisses
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Caisses", inversedBy="journeeCaisses", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $idCaisse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateurs", inversedBy="journeeCaisses", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses")
     * @ORM\JoinColumn(nullable=true)
     */
    private $idJourneeSuivante;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateOuv;

    /**
     * @ORM\Column(type="string")
     */
    private $statut='O';

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Billetages")
     * @ORM\JoinColumn(nullable=true)
     */
    private $idBilletOuv;

    /**
     * @ORM\Column(type="bigint")
     */
    private $valeurBillet=0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SystemElectInventaires")
     * @ORM\JoinColumn(nullable=true)
     */
    private $idSystemElectInventOuv;

    /**
     * @ORM\Column(type="bigint")
     */
    private $soldeElectOuv=0;

    /**
     * @ORM\Column(type="bigint")
     */
    private $ecartOuv=0;

    /**
     * @ORM\Column(type="bigint")
     */
    private $mCvd=0;

    /**
     * @ORM\Column(type="bigint")
     */
    private $mEmissionTrans=0;

    /**
     * @ORM\Column(type="bigint")
     */
    private $mReceptionTrans=0;

    /**
     * @ORM\Column(type="bigint")
     */
    private $mIntercaisse=0;

    /**
     * @ORM\Column(type="bigint")
     */
    private $mRetraitClient=0;

    /**
     * @ORM\Column(type="bigint")
     */
    private $mDepotClient=0;

    /**
     * @ORM\Column(type="bigint")
     */
    private $mCreditDivers=0;

    /**
     * @ORM\Column(type="bigint")
     */
    private $mDetteDivers=0;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateFerm;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Billetages")
     * @ORM\JoinColumn(nullable=true)
     */
    private $idBilletFerm;

    /**
     * @ORM\Column(type="bigint")
     */
    private $valeurBilletFerm=0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SystemElectInventaires")
     * @ORM\JoinColumn(nullable=true)
     */
    private $idSystemElectInventFerm;

    /**
     * @ORM\Column(type="bigint")
     */
    private $SoldeElectFerm=0;

    /**
     * @ORM\Column(type="bigint")
     */
    private $mEcartFerm=0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DeviseJournees", mappedBy="idJourneeCaisse", cascade={"persist"})
     */
    private $deviseJournee;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TransfertInternationaux", mappedBy="idJourneeCaisse", cascade={"persist"})
     */
    private $transfertInternationaux;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InterCaisses", mappedBy="journeeCaisseSortant", cascade={"persist"})
     */
    private $intercaisseSortant;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InterCaisses", mappedBy="journeeCaisseEntrant", cascade={"persist"})
     */
    private $intercaisseEntrant;

    /**
     * Get deviseJournees
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDeviseJournee()
    {
        return $this->deviseJournee;
    }

    /**
     * @param mixed $deviseJournee
     * @return JourneeCaisses
     */
    public function setDeviseJournee($deviseJournee)
    {
        $this->deviseJournee = $deviseJournee;
        return $this;
    }


    /**
     * JourneeCaisses constructor.
     */
    public function __construct()
    {
        //$this->deviseJournee = new ArrayCollection();
        $this->transfertInternationaux=new ArrayCollection();
        $this->intercaisseEntrant=new ArrayCollection();
        $this->intercaisseSortant=new ArrayCollection();
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
    public function getIdCaisse()
    {
        return $this->idCaisse;
    }

    /**
     * @param mixed $idCaisse
     */
    public function setIdCaisse($idCaisse)
    {
        $this->idCaisse = $idCaisse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * @param mixed $utilisateur
     * @return JourneeCaisses
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdJourneeSuivante()
    {
        return $this->idJourneeSuivante;
    }

    /**
     * @param mixed $idJourneeSuivante
     */
    public function setIdJourneeSuivante($idJourneeSuivante)
    {
        $this->idJourneeSuivante = $idJourneeSuivante;
    }

    /**
     * @return mixed
     */
    public function getDateOuv()
    {
        return $this->dateOuv;
    }

    /**
     * @param mixed $dateOuv
     */
    public function setDateOuv($dateOuv)
    {
        $this->dateOuv = $dateOuv;
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
     */
    public function setIdBilletOuv($idBilletOuv)
    {
        $this->idBilletOuv = $idBilletOuv;
    }

    /**
     * @return mixed
     */
    public function getValeurBillet()
    {
        return $this->valeurBillet;
    }

    /**
     * @param mixed $valeurBillet
     */
    public function setValeurBillet($valeurBillet)
    {
        $this->valeurBillet = $valeurBillet;
    }

    /**
     * @return mixed
     */
    public function getIdSystemElectInventOuv()
    {
        return $this->idSystemElectInventOuv;
    }

    /**
     * @param mixed $idSystemElectInventOuv
     */
    public function setIdSystemElectInventOuv($idSystemElectInventOuv)
    {
        $this->idSystemElectInventOuv = $idSystemElectInventOuv;
    }

    /**
     * @return mixed
     */
    public function getSoldeElectOuv()
    {
        return $this->soldeElectOuv;
    }

    /**
     * @param mixed $soldeElectOuv
     */
    public function setSoldeElectOuv($soldeElectOuv)
    {
        $this->soldeElectOuv = $soldeElectOuv;
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
    public function getMCvd()
    {
        return $this->mCvd;
    }

    /**
     * @param mixed $mCvd
     */
    public function setMCvd($mCvd)
    {
        $this->mCvd = $mCvd;
    }

    /**
     * @return mixed
     */
    public function getMEmissionTrans()
    {
        return $this->mEmissionTrans;
    }

    /**
     * @param mixed $mEmissionTrans
     */
    public function setMEmissionTrans($mEmissionTrans)
    {
        $this->mEmissionTrans = $mEmissionTrans;
    }

    /**
     * @return mixed
     */
    public function getMReceptionTrans()
    {
        return $this->mReceptionTrans;
    }

    /**
     * @param mixed $mReceptionTrans
     */
    public function setMReceptionTrans($mReceptionTrans)
    {
        $this->mReceptionTrans = $mReceptionTrans;
    }

    /**
     * @return mixed
     */
    public function getMIntercaisse()
    {
        return $this->mIntercaisse;
    }

    /**
     * @param mixed $mIntercaisse
     */
    public function setMIntercaisse($mIntercaisse)
    {
        $this->mIntercaisse = $mIntercaisse;
    }

    /**
     * @return mixed
     */
    public function getMRetraitClient()
    {
        return $this->mRetraitClient;
    }

    /**
     * @param mixed $mRetraitClient
     */
    public function setMRetraitClient($mRetraitClient)
    {
        $this->mRetraitClient = $mRetraitClient;
    }

    /**
     * @return mixed
     */
    public function getMDepotClient()
    {
        return $this->mDepotClient;
    }

    /**
     * @param mixed $mDepotClient
     */
    public function setMDepotClient($mDepotClient)
    {
        $this->mDepotClient = $mDepotClient;
    }

    /**
     * @return mixed
     */
    public function getMCreditDivers()
    {
        return $this->mCreditDivers;
    }

    /**
     * @param mixed $mCreditDivers
     */
    public function setMCreditDivers($mCreditDivers)
    {
        $this->mCreditDivers = $mCreditDivers;
    }

    /**
     * @return mixed
     */
    public function getMDetteDivers()
    {
        return $this->mDetteDivers;
    }

    /**
     * @param mixed $mDetteDivers
     */
    public function setMDetteDivers($mDetteDivers)
    {
        $this->mDetteDivers = $mDetteDivers;
    }

    /**
     * @return mixed
     */
    public function getDateFerm()
    {
        return $this->dateFerm;
    }

    /**
     * @param mixed $dateFerm
     */
    public function setDateFerm($dateFerm)
    {
        $this->dateFerm = $dateFerm;
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
    public function getValeurBilletFerm()
    {
        return $this->valeurBilletFerm;
    }

    /**
     * @param mixed $valeurBilletFerm
     */
    public function setValeurBilletFerm($valeurBilletFerm)
    {
        $this->valeurBilletFerm = $valeurBilletFerm;
    }

    /**
     * @return mixed
     */
    public function getIdSystemElectInventFerm()
    {
        return $this->idSystemElectInventFerm;
    }

    /**
     * @param mixed $idSystemElectInventFerm
     */
    public function setIdSystemElectInventFerm($idSystemElectInventFerm)
    {
        $this->idSystemElectInventFerm = $idSystemElectInventFerm;
    }

    /**
     * @return mixed
     */
    public function getSoldeElectFerm()
    {
        return $this->SoldeElectFerm;
    }

    /**
     * @param mixed $SoldeElectFerm
     */
    public function setSoldeElectFerm($SoldeElectFerm)
    {
        $this->SoldeElectFerm = $SoldeElectFerm;
    }

    /**
     * @return mixed
     */
    public function getMEcartFerm()
    {
        return $this->mEcartFerm;
    }

    /**
     * @param mixed $mEcartFerm
     */
    public function setMEcartFerm($mEcartFerm)
    {
        $this->mEcartFerm = $mEcartFerm;
    }

    public function __toString()
    {
        return ''.$this->getIdCaisse();
    }


    /**
     * @return mixed
     */
    public function getTransfertInternationaux()
    {
        return $this->transfertInternationaux;
    }

    /**
     * @return mixed
     */
    public function getIntercaisseSortant()
    {
        return $this->intercaisseSortant;
    }

    /**
     * @param mixed $intercaisseSortant
     * @return JourneeCaisses
     */
    public function setIntercaisseSortant($intercaisseSortant)
    {
        $this->intercaisseSortant = $intercaisseSortant;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIntercaisseEntrant()
    {
        return $this->intercaisseEntrant;
    }

    /**
     * @param mixed $intercaisseEntrant
     * @return JourneeCaisses
     */
    public function setIntercaisseEntrant($intercaisseEntrant)
    {
        $this->intercaisseEntrant = $intercaisseEntrant;
        return $this;
    }



    /**
     * @param mixed $transfertInternationaux
     * @return JourneeCaisses
     */
    public function setTransfertInternationaux($transfertInternationaux)
    {
        $this->transfertInternationaux = $transfertInternationaux;
        return $this;
    }

    public function addDeviseJournee(DeviseJournees $deviseJournees)
    {
        $this->deviseJournee->add($this->deviseJournee);
        $deviseJournees->setIdJourneeCaisse($this);
    }

    public function removeDeviseJournee(DeviseJournees $deviseJournees)
    {
        $this->deviseJournee->removeElement($deviseJournees);
    }

    public function addTransfertInternationaux(TransfertInternationaux $transfertInternationaux)
    {
        $this->transfertInternationaux->add($transfertInternationaux);
        $transfertInternationaux->setIdJourneeCaisse($this);
    }

    public function removeTransfertInternationaux(TransfertInternationaux $transfertInternationaux)
    {
        $this->transfertInternationaux->removeElement($transfertInternationaux);
    }

    public function addInterCaisseSortant(InterCaisses $interCaisses)
    {
        $this->intercaisseSortant->add($interCaisses);
        $interCaisses->setJourneeCaisseSortant($this);
    }

    public function removeInterCaisseSortant(InterCaisses $interCaisses)
    {
        $this->intercaisseSortant->removeElement($interCaisses);
    }

    public function addInterCaisseDestination(InterCaisses $interCaisses)
    {
        $this->intercaisseEntrant->add($interCaisses);
        $interCaisses->setJourneeCaisseEntrant($this);
    }

    public function removeInterCaisseEntrant(InterCaisses $interCaisses)
    {
        $this->intercaisseEntrant->removeElement($interCaisses);
    }

}