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
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

/**
 * @ORM\Entity (repositoryClass="App\Repository\JourneeCaissesRepository")
 * @ORM\Table(name="JourneeCaisses")
 * @ORM\HasLifecycleCallbacks()
 */
class JourneeCaisses
{
    const OUVERT='O', FERME='F', INITIAL='I';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Caisses", inversedBy="journeeCaisses", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     */
    private $caisse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateurs", inversedBy="journeeCaisses", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     */
    private $utilisateur;


    /**
     * @ORM\Column(type="string")
     */
    private $statut=self::INITIAL;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\JourneeCaisses")
     * @ORM\JoinColumn(nullable=true)
     */
    private $journeePrecedente;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateOuv;


    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Billetages")
     * @ORM\JoinColumn(nullable=true)
     */
    private $billetOuv;

    /**
     * @ORM\Column(type="bigint")
     */
    private $mLiquiditeOuv=0;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\SystemElectInventaires")
     * @ORM\JoinColumn(nullable=true)
     */
    private $systemElectInventOuv;

    /**
     * @ORM\Column(type="bigint")
     */
    private $mSoldeElectOuv=0;

    /**
     * @ORM\Column(type="bigint")
     */
    private $mEcartOuv=0;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateFerm;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Billetages")
     * @ORM\JoinColumn(nullable=true)
     */
    private $billetFerm;

    /**
     * @ORM\Column(type="bigint")
     */
    private $mLiquiditeFerm=0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SystemElectInventaires")
     * @ORM\JoinColumn(nullable=true)
     */
    private $systemElectInventFerm;

    /**
     * @ORM\Column(type="bigint")
     */
    private $mSoldeElectFerm=0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DetteCreditDivers", mappedBy="journeeCaissesCreation", cascade={"persist"})
     */
    private $detteCreditCreations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DetteCreditDivers", mappedBy="journeeCaissesRemb", cascade={"persist"})
     */
    private $detteCreditRembs;

    /**
     * @ORM\Column(type="bigint")
     */
    private $mDetteDivers=0;

    /**
     * @ORM\Column(type="bigint")
     */
    private $mCreditDivers=0;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InterCaisses", mappedBy="journeeCaisseEntrant", cascade={"persist"})
     */
    private $intercaisseEntrants;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InterCaisses", mappedBy="journeeCaisseSortant", cascade={"persist"})
     */
    private $intercaisseSortants;

    /**
     * @ORM\Column(type="bigint")
     */
    private $mIntercaisses=0;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TransfertInternationaux", mappedBy="idJourneeCaisse", cascade={"persist"})
     */
    private $transfertInternationaux;

    /**
     * @ORM\Column(type="bigint")
     */
    private $mEmissionTrans=0;

    /**
     * @ORM\Column(type="bigint")
     */
    private $mReceptionTrans=0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DeviseMouvements", mappedBy="journeeCaisse", cascade={"persist"})
     */
    private $deviseMouvements;

    /**
     * @ORM\Column(type="bigint")
     */
    private $mCvd=0;

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
    private $mEcartFerm=0;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DeviseIntercaisses", mappedBy="journeeCaisseSource", cascade={"persist"})
     */
    private $deviseIntercaisseSortants;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DeviseIntercaisses", mappedBy="journeeCaisseDestination", cascade={"persist"})
     */
    private $deviseIntercaisseEntrants;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DeviseJournees", mappedBy="journeeCaisse", cascade={"persist"})
     */
    private $deviseJournees;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DeviseRecus", mappedBy="journeeCaisse", cascade={"persist"})
     */
    private $deviseRecus;


    /**
     * JourneeCaisses constructor.
     */
    public function __construct()
    {
        //$this->deviseJournee = new ArrayCollection();
        $this->transfertInternationaux=new ArrayCollection();
        $this->intercaisseEntrant=new ArrayCollection();
        $this->intercaisseSortant=new ArrayCollection();
        $this->deviseRecus=new ArrayCollection();
        $this->deviseJournee=new ArrayCollection();

    }

    /**
     * @ORM\PreUpdate
     */
    public function updateMEcarts(){
        $this->setMEcarts();

    }

    /**
     * @ORM\PrePersist
     */
    public function setMEcarts(){
        $this->setMEcartOuv();
        $this->setMEcartFerm();
    }

    public function getMEcartFerm(){
        return $this->mEcartFerm;
    }

    public function setMEcartFerm()
    {
        $this->mEcartFerm = $this->getSoldeNetOuv()-$this->getSoldeNetFerm();
        return $this;
    }



    public function getMEcartOuv()
    {
        return $this->mEcartOuv;
    }

    public function setMEcartOuv()
    {
        ($this->journeePrecedente!=null)?$soldeNetFerm=$this->journeePrecedente->getSoldeNetFerm():$soldeNetFerm=0;
        $this->mEcartOuv = $soldeNetFerm - $this->getSoldeNetOuv();
        return $this;
    }


    public function getDisponibliteFerm(){
        return $this->getMLiquiditeFerm()
        + $this->getMSoldeElectFerm();
    }

    public function getSoldeNetFerm(){
        return
            ($this->getDisponibliteFerm()
            + $this->getMDetteDivers()
            + $this->getMEmissionTrans()
            + $this->getMCvd()
            + $this->getMDepotClient()) -
            ( $this->getMCreditDivers()
            + $this->getMReceptionTrans()
            + $this->getMRetraitClient()
            );

    }

    public function getDisponibliteOuv(){
        return $this->getMLiquiditeOuv()
        + $this->getMSoldeElectOuv();
    }

    public function getSoldeNetOuv(){
        if ($this->journeePrecedente!=null){
            $detteDivers=$this->journeePrecedente->getMDetteDivers();
            $creditDivers=$this->journeePrecedente->getMCreditDivers();
        }else{
            $detteDivers=0;
            $creditDivers=0;
        }

        return $this->getDisponibliteOuv() +$detteDivers - $creditDivers;
    }


    public function updateM($champ,$montant){
        $this->$champ+=$montant;
    }

    public function maintenirMCvd(){
        $this->mCvd=0;
        foreach ($this->getDeviseMouvements() as $deviseMouvement){
            $this->updateM('mCvd', $deviseMouvement->getContreValeur());
        }
    }


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * @param $utilisateur
     * @return $this
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateOuv()
    {
        return $this->dateOuv;
    }

    /**
     * @param $dateOuv
     * @return mixed
     */
    public function setDateOuv($dateOuv)
    {
        return $this->dateOuv = $dateOuv;
    }

    /**
     * @return mixed
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param $statut
     * @return $this
     */
    public function setStatut($statut)
    {
        if ($statut==$this::OUVERT ) {
            $this->caisse->setJourneeOuverte($this);
        }else{
            $this->caisse->setJourneeOuverte(null);
        }
        $this->statut = $statut;
        return $this;
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

     public function setMCreditDivers($mCreditDivers)
    {
        $this->mCreditDivers = $mCreditDivers;
        return $this;
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
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateFerm()
    {
        return $this->dateFerm;
    }

    /**
     * @param $dateFerm
     * @return mixed
     */
    public function setDateFerm($dateFerm)
    {
        return $this->dateFerm = $dateFerm;
    }

    public function __toString()
    {
        return ''.$this->getCaisse().' du '.$this->getDateOuv()->format('d-m-y');
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
     * @return mixed
     */
    public function getDeviseRecus()
    {
        return $this->deviseRecus;
    }

    /**
     * @param mixed $deviseRecus
     * @return JourneeCaisses
     */
    public function setDeviseRecus($deviseRecus)
    {
        $this->deviseRecus = $deviseRecus;
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

    public function addDeviseJournee(DeviseJournees $deviseJournee)
    {
        $deviseJournee->setJourneeCaisse($this);
        $this->deviseJournee->add($deviseJournee);
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

    /**
     * @param DeviseRecus $deviseRecu
     */
    public function addDeviseRecu(DeviseRecus $deviseRecu)
    {
        $deviseRecu->setJourneeCaisse($this);
        $this->deviseRecus->add($deviseRecu);

    }

    public function removeDeviseRecu(DeviseRecus $deviseRecu)
    {
        $this->deviseRecus->removeElement($deviseRecu);
    }

    public function getJourneeCaisse(){
        return $this->__toString();
    }

    /**
     * @return mixed
     */
    public function getDeviseJournees()
    {
        return $this->deviseJournees;
    }

    /**
     * @param mixed $deviseJournees
     * @return JourneeCaisses
     */
    public function setDeviseJournees($deviseJournees)
    {
        $this->deviseJournees = $deviseJournees;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeviseIntercaisseSortants()
    {
        return $this->deviseIntercaisseSortants;
    }

    /**
     * @param mixed $deviseIntercaisseSortants
     * @return JourneeCaisses
     */
    public function setDeviseIntercaisseSortants($deviseIntercaisseSortants)
    {
        $this->deviseIntercaisseSortants = $deviseIntercaisseSortants;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeviseIntercaisseEntrants()
    {
        return $this->deviseIntercaisseEntrants;
    }

    /**
     * @param mixed $deviseIntercaisseEntrants
     * @return JourneeCaisses
     */
    public function setDeviseIntercaisseEntrants($deviseIntercaisseEntrants)
    {
        $this->deviseIntercaisseEntrants = $deviseIntercaisseEntrants;
        return $this;
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
     * @return JourneeCaisses
     */
    public function setDeviseMouvements($deviseMouvements)
    {
        $this->deviseMouvements = $deviseMouvements;
        return $this;
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
     * @return JourneeCaisses
     */
    public function setCaisse($caisse)
    {
        $this->caisse = $caisse;
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
     * @return JourneeCaisses
     */
    public function setBilletOuv($billetOuv)
    {
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
     * @return JourneeCaisses
     */
    public function setBilletFerm($billetFerm)
    {
        $this->billetFerm = $billetFerm;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDetteCreditCreations()
    {
        return $this->detteCreditCreations;
    }

    /**
     * @param mixed $detteCreditCreations
     * @return JourneeCaisses
     */
    public function setDetteCreditCreations($detteCreditCreations)
    {
        $this->detteCreditCreations = $detteCreditCreations;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDetteCreditRembs()
    {
        return $this->detteCreditRembs;
    }

    /**
     * @param mixed $detteCreditRembs
     * @return JourneeCaisses
     */
    public function setDetteCreditRembs($detteCreditRembs)
    {
        $this->detteCreditRembs = $detteCreditRembs;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSystemElectInventOuv()
    {
        return $this->systemElectInventOuv;
    }

    /**
     * @param mixed $systemElectInventOuv
     * @return JourneeCaisses
     */
    public function setSystemElectInventOuv($systemElectInventOuv)
    {
        $this->systemElectInventOuv = $systemElectInventOuv;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSystemElectInventFerm()
    {
        return $this->systemElectInventFerm;
    }

    /**
     * @param mixed $systemElectInventFerm
     * @return JourneeCaisses
     */
    public function setSystemElectInventFerm($systemElectInventFerm)
    {
        $this->systemElectInventFerm = $systemElectInventFerm;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIntercaisseEntrants()
    {
        return $this->intercaisseEntrants;
    }

    /**
     * @param mixed $intercaisseEntrants
     * @return JourneeCaisses
     */
    public function setIntercaisseEntrants($intercaisseEntrants)
    {
        $this->intercaisseEntrants = $intercaisseEntrants;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIntercaisseSortants()
    {
        return $this->intercaisseSortants;
    }

    /**
     * @param mixed $intercaisseSortants
     * @return JourneeCaisses
     */
    public function setIntercaisseSortants($intercaisseSortants)
    {
        $this->intercaisseSortants = $intercaisseSortants;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMLiquiditeOuv()
    {
        return $this->mLiquiditeOuv;
    }

    /**
     * @param mixed $mLiquiditeOuv
     * @return JourneeCaisses
     */
    public function setMLiquiditeOuv($mLiquiditeOuv)
    {
        $this->mLiquiditeOuv = $mLiquiditeOuv;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMSoldeElectOuv()
    {
        return $this->mSoldeElectOuv;
    }

    /**
     * @param mixed $mSoldeElectOuv
     * @return JourneeCaisses
     */
    public function setMSoldeElectOuv($mSoldeElectOuv)
    {
        $this->mSoldeElectOuv = $mSoldeElectOuv;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getMLiquiditeFerm()
    {
        return $this->mLiquiditeFerm;
    }

    /**
     * @param mixed $mLiquiditeFerm
     * @return JourneeCaisses
     */
    public function setMLiquiditeFerm($mLiquiditeFerm)
    {
        $this->mLiquiditeFerm = $mLiquiditeFerm;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMSoldeElectFerm()
    {
        return $this->mSoldeElectFerm;
    }

    /**
     * @param mixed $mSoldeElectFerm
     * @return JourneeCaisses
     */
    public function setMSoldeElectFerm($mSoldeElectFerm)
    {
        $this->mSoldeElectFerm = $mSoldeElectFerm;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMIntercaisses()
    {
        return $this->mIntercaisses;
    }

    /**
     * @param mixed $mIntercaisses
     * @return JourneeCaisses
     */
    public function setMIntercaisses($mIntercaisses)
    {
        $this->mIntercaisses = $mIntercaisses;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJourneePrecedente()
    {
        return $this->journeePrecedente;
    }

    /**
     * @param mixed $journeePrecedente
     * @return JourneeCaisses
     */
    public function setJourneePrecedente($journeePrecedente)
    {
        $this->journeePrecedente = $journeePrecedente;
        return $this;
    }

    

}