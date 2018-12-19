<?php
/**
 * Created by PhpStorm.
 * User: Mouni
 * Date: 22/11/2016
 * Time: 10:39
 */

namespace App\Entity;



use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @GRID\Source(columns="id, caisse, utilisateur, dateOuv")
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
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $journeePrecedente;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateOuv;


    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Billetages", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $billetOuv;

    /**
     * @ORM\Column(type="bigint")
     * @Assert\GreaterThanOrEqual(value="0", message="la valeur doit positive")
     */
    private $mLiquiditeOuv=0;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\SystemElectInventaires", inversedBy="journeeCaisse", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $systemElectInventOuv;

    /**
     * @ORM\Column(type="bigint")
     * @Assert\GreaterThanOrEqual(value="0", message="la valeur doit positive")
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
     * @ORM\OneToOne(targetEntity="App\Entity\Billetages", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $billetFerm;

    /**
     * @ORM\Column(type="bigint")
     * @Assert\GreaterThanOrEqual(value="0", message="la valeur doit positive")
     */
    private $mLiquiditeFerm=0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SystemElectInventaires", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $systemElectInventFerm;

    /**
     * @ORM\Column(type="bigint")
     * @Assert\GreaterThanOrEqual(value="0", message="la valeur doit positive")
     */
    private $mSoldeElectFerm=0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DetteCreditDivers", mappedBy="journeeCaisse", cascade={"persist"})
     */
    private $detteCredits;

    /*
     * @ORM\OneToMany(targetEntity="App\Entity\DetteCreditDivers", mappedBy="journeeCaissesRemb", cascade={"persist"})

    private $detteCreditRembs;
*/
    /**
     * @ORM\Column(type="bigint")
     * @Assert\GreaterThanOrEqual(value="0", message="la valeur doit positive")
     */
    private $mDetteDiversOuv=0;

    /**
     * @ORM\Column(type="bigint")
     * @Assert\GreaterThanOrEqual(value="0", message="la valeur doit positive")
     */
    private $mCreditDiversOuv=0;

    /**
     * @ORM\Column(type="bigint")
     * @Assert\GreaterThanOrEqual(value="0", message="la valeur doit positive")
     */
    private $mDetteDiversFerm=0;

    /**
     * @ORM\Column(type="bigint")
     * @Assert\GreaterThanOrEqual(value="0", message="la valeur doit positive")
     */
    private $mCreditDiversFerm=0;


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
     * @ORM\Column(type="bigint")
     * @Assert\GreaterThanOrEqual(value="0", message="la valeur doit positive")
     */
    private $mIntercaisseSortants=0;

    /**
     * @ORM\Column(type="bigint")
     * @Assert\GreaterThanOrEqual(value="0", message="la valeur doit positive")
     */
    private $mIntercaisseEntrants=0;


    /*
     * @ORM\OneToMany(targetEntity="App\Entity\TransfertInternationaux", mappedBy="journeeCaisse", cascade={"persist"})
     */
    private $transfertInternationaux;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TransfertInternationaux", mappedBy="journeeCaisseEmi", cascade={"persist"})
     */
    private $transfertEmis;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TransfertInternationaux", mappedBy="journeeCaisseRecu", cascade={"persist"})
     */
    private $transfertRecus;

    /**
     * @ORM\Column(type="bigint")
     * @Assert\GreaterThanOrEqual(value="0", message="la valeur doit positive")
     */
    private $mEmissionTrans=0;

    /**
     * @ORM\Column(type="bigint")
     * @Assert\GreaterThanOrEqual(value="0", message="la valeur doit positive")
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
     * @Assert\GreaterThanOrEqual(value="0", message="la valeur doit positive")
     */
    private $mRetraitClient=0;

    /**
     * @ORM\Column(type="bigint")
     * @Assert\GreaterThanOrEqual(value="0", message="la valeur doit positive")
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
     * @ORM\OneToMany(targetEntity="App\Entity\Transactions", mappedBy="journeeCaisse", cascade={"persist"})
     */
    private $transactions;

    private $em;

    private $mouvementFond;

    private $sensTransfert;

    /*
    *
     * @ORM\Column(type="bigint")

    private $mIntercaisseDevise=0;
*/

//    /**
//     * @ORM\OneToMany(targetEntity="App\Entity\Transactions", mappedBy="journeeCaisse", cascade={"persist"})
//     */
//    private $depots;
//
//    /**
//     * @ORM\OneToMany(targetEntity="App\Entity\Transactions", mappedBy="journeeCaisse", cascade={"persist"})
//     */
//    private $retraits;



    /**
     * JourneeCaisses constructor.
     */
    public function __construct(ObjectManager $em)
    {
        $this->em=$em;
        $this->transfertInternationaux=new ArrayCollection();
        $this->transfertEmis=new ArrayCollection();
        $this->transfertRecus=new ArrayCollection();
        $this->intercaisseEntrants=new ArrayCollection();
        $this->intercaisseSortants=new ArrayCollection();
        $this->deviseRecus=new ArrayCollection();
        $this->deviseJournees=new ArrayCollection();
        $this->transactions=new ArrayCollection();
        $this->detteCredits=new ArrayCollection();

        $this->billetOuv=new Billetages();
        $this->systemElectInventOuv=new SystemElectInventaires();
        $this->billetFerm=new Billetages();
        $this->systemElectInventFerm=new SystemElectInventaires();
        ///$devises = $this->em->getRepository(Devises::class)->findAll();
        /*foreach ($devises as $devise){
            $deviseJournee = new DeviseJournees($this,$devise);
            $this->addDeviseJournee($deviseJournee);
            //$this->em->persist($deviseJournee);
        }*/

        //$this->em->persist($this);



    }

    /**
     * @ORM\PreUpdate
     */
    public function updateMEcarts(){
        $this->setMEcarts();
        $this->setMLiquiditeOuv($this->billetOuv->getValeurTotal());
        $this->setMIntercaisses($this->getmIntercaisseEntrants()-$this->getmIntercaisseSortants());
    }

    /**
     * @ORM\PrePersist
     */
    public function setMEcarts(){
        $this->setDateOuv(new \DateTime('now'));
        $this->setMEcartOuv();
        $this->setMEcartFerm();
        //$this->maintenirDetteCreditDiversFerm();
    }

    public function getMEcartFerm(){
        return $this->mEcartFerm;
    }

    public function setMEcartFerm()
    {
        $this->mEcartFerm = $this->getSoldeNetFerm() - $this->getSoldeNetOuv() - $this->getMouvementFond();
        //dump($this->mEcartFerm);die();
        return $this;
    }

    public function getMEcartOuv()
    {
        return $this->mEcartOuv;
    }

    public function setMEcartOuv()
    {
        ($this->journeePrecedente!=null)?$soldeNetFerm=$this->journeePrecedente->getSoldeNetFerm():$soldeNetFerm=0;
        $this->mEcartOuv = $this->getSoldeNetOuv() - $soldeNetFerm ;
        return $this;
    }


    public function getDisponibiliteFerm(){
        return $this->getMLiquiditeFerm()
        + $this->getMSoldeElectFerm();
    }

    public function getSoldeNetFerm(){
        return
            ($this->getDisponibiliteFerm()
                + $this->getMDetteDiversFerm()
                - $this->getMCreditDiversFerm()
            );

    }


    public function getMouvementFond(){
        return $this->mouvementFond =
            + $this->getMEmissionTrans()
            - $this->getMReceptionTrans()
            + $this->getMCvd()
            + $this->getMIntercaisseEntrants()
            - $this->getMIntercaisseSortants()
            + $this->getMDepotClient()
            - $this->getMRetraitClient()
            ;

    }

    public function getDisponibiliteOuv(){
        return $this->getMLiquiditeOuv()
        + $this->getMSoldeElectOuv();
    }

    public function getSoldeNetOuv(){
        /*if ($this->journeePrecedente!=null){
            $detteDivers=$this->journeePrecedente->getMDetteDiversFerm();
            $creditDivers=$this->journeePrecedente->getMCreditDiversFerm();
        }else{
            $detteDivers=0;
            $creditDivers=0;
        }*/

        return $this->getDisponibiliteOuv() + $this->getMDetteDiversOuv() - $this->getMCreditDiversOuv();
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


    public function maintenirDetteCreditDiversFerm(){
        $this->mCreditDiversFerm=0;
        $this->mDetteDiversFerm=0;
        foreach ($this->getDetteCredits() as $detteCredit){
            if ($detteCredit->getStatut()==DetteCreditDivers::DETTE_EN_COUR) {
                //dump($detteCredit->getMCredit());
                $this->updateM('mDetteDiversFerm', $detteCredit->getMDette());
            }
            elseif ($detteCredit->getStatut()==DetteCreditDivers::CREDIT_EN_COUR)
                $this->updateM('mCreditDiversFerm', $detteCredit->getMCredit());
            }
            die();
    }


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
        $this->deviseJournees->add($deviseJournee);
    }

    public function removeDeviseJournee(DeviseJournees $deviseJournees)
    {
        $this->deviseJournees->removeElement($deviseJournees);
    }

    public function addTransfertInternationaux(TransfertInternationaux $transfertInternationaux)
    {

        $transfertInternationaux->setSens($this->getSensTransfert());
        $this->transfertInternationaux->add($transfertInternationaux);
        $transfertInternationaux->setIdJourneeCaisse($this);

    }

    public function removeTransfertInternationaux(TransfertInternationaux $transfertInternationaux)
    {
        $this->transfertInternationaux->removeElement($transfertInternationaux);
    }
    public function addTransfertEmi(TransfertInternationaux $transfertInternationaux)
    {

        $transfertInternationaux->setSens($this->getSensTransfert());
        $this->transfertEmis->add($transfertInternationaux);
        $transfertInternationaux->setJourneeCaisseEmi($this);
        $transfertInternationaux->setJourneeCaisse($this);

    }

    public function removeTransfertEmi(TransfertInternationaux $transfertInternationaux)
    {
        $this->transfertEmis->removeElement($transfertInternationaux);
    }
    public function addTransfertRecu(TransfertInternationaux $transfertInternationaux)
    {
        $transfertInternationaux->setSens($this->getSensTransfert());
        $transfertInternationaux->setMTransfertTTC($transfertInternationaux->getMTransfert());
        $this->transfertRecus->add($transfertInternationaux);
        $transfertInternationaux->setJourneeCaisseRecu($this);
        $transfertInternationaux->setJourneeCaisse($this);
        //dump($transfertInternationaux); die();

    }

    public function removeTransfertRecu(TransfertInternationaux $transfertInternationaux)
    {
        $this->transfertRecus->removeElement($transfertInternationaux);
    }

    public function addInterCaisseSortant(InterCaisses $interCaisses)
    {
        $this->intercaisseSortants->add($interCaisses);
        $interCaisses->setJourneeCaisseSortant($this);
    }

    public function removeInterCaisseSortant(InterCaisses $interCaisses)
    {
        $this->intercaisseSortants->removeElement($interCaisses);
    }

    public function addInterCaisseEntrant(InterCaisses $interCaisses)
    {
        $this->intercaisseEntrants->add($interCaisses);
        $interCaisses->setJourneeCaisseEntrant($this);
    }

    public function removeInterCaisseEntrant(InterCaisses $interCaisses)
    {
        $this->intercaisseEntrants->removeElement($interCaisses);
    }

    public function addInterCaisseDestination(InterCaisses $interCaisses)
    {
        $this->intercaisseEntrants->add($interCaisses);
        $interCaisses->setJourneeCaisseEntrant($this);
    }


    /**
     * @param DeviseRecus $deviseRecu
     */
    public function addDeviseRecu(DeviseRecus $deviseRecu)
    {
        $deviseRecu->setJourneeCaisse($this);
        $this->deviseRecus->add($deviseRecu);

    }

    /**
     * @param DeviseRecus $deviseRecu
     */
    public function removeDeviseRecu(DeviseRecus $deviseRecu)
    {
        $this->deviseRecus->removeElement($deviseRecu);
    }

    /**
     * @param Transactions $transaction
     */
    public function addTransaction(Transactions $transaction)
    {
        $this->transactions->add($transaction);
        $transaction->setJourneeCaisse($this);
    }

    /**
     * @param Transactions $transaction
     */
    public function removeTransaction(Transactions $transaction)
    {
        $this->transactions->removeElement($transaction);
    }

    /**
     * @param Transactions $transaction
     */
    public function addDetteCredit(DetteCreditDivers $detteCreditDiver)
    {
        $this->detteCredits->add($detteCreditDiver);
        $detteCreditDiver->setJourneeCaisse($this);
    }

    /**
     * @param Transactions $transaction
     */
    public function removeDetteCredit(DetteCreditDivers $detteCreditDiver)
    {
        $this->transactions->removeElement($detteCreditDiver);
    }


    public function getJourneeCaisse(){
        return $this;
    }



    /**
     * Get deviseJournees
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDeviseJournee()
    {
        return $this->deviseJournees;
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
            $this->caisse->setJourneeOuverteId($this->getId());
        }/*else{
            $this->caisse->setJourneeOuverteId($this->getId());
        }*/
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
     * @return JourneeCaisses
     */
    public function setMRetraitClient($mRetraitClient)
    {
        $this->mRetraitClient = $mRetraitClient;
        return $this;
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
     * @return JourneeCaisses
     */
    public function setMDepotClient($mDepotClient)
    {
        $this->mDepotClient = $mDepotClient;
        return $this;
    }

    /**
     * @return ObjectManager
     */
    public function getEm(): ObjectManager
    {
        return $this->em;
    }

    /**
     * @param ObjectManager $em
     * @return JourneeCaisses
     */
    public function setEm(ObjectManager $em): JourneeCaisses
    {
        $this->em = $em;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getMDetteDiversOuv()
    {
        return $this->mDetteDiversOuv;
    }

    /**
     * @param mixed $mDetteDiversOuv
     * @return JourneeCaisses
     */
    public function setMDetteDiversOuv($mDetteDiversOuv)
    {
        $this->mDetteDiversOuv = $mDetteDiversOuv;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMCreditDiversOuv()
    {
        return $this->mCreditDiversOuv;
    }

    /**
     * @param mixed $mCreditDiversOuv
     * @return JourneeCaisses
     */
    public function setMCreditDiversOuv($mCreditDiversOuv)
    {
        $this->mCreditDiversOuv = $mCreditDiversOuv;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMDetteDiversFerm()
    {
        return $this->mDetteDiversFerm;
    }

    /**
     * @param mixed $mDetteDiversFerm
     * @return JourneeCaisses
     */
    public function setMDetteDiversFerm($mDetteDiversFerm)
    {
        $this->mDetteDiversFerm = $mDetteDiversFerm;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMCreditDiversFerm()
    {
        return $this->mCreditDiversFerm;
    }

    /**
     * @param mixed $mCreditDiversFerm
     * @return JourneeCaisses
     */
    public function setMCreditDiversFerm($mCreditDiversFerm)
    {
        $this->mCreditDiversFerm = $mCreditDiversFerm;
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
    public function getTransfertEmis()
    {
        return $this->transfertEmis;
    }

    /**
     * @param mixed $transfertEmis
     * @return JourneeCaisses
     */
    public function setTransfertEmis($transfertEmis)
    {
        $this->transfertEmis = $transfertEmis;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransfertRecus()
    {
        return $this->transfertRecus;
    }

    /**
     * @param mixed $transfertRecus
     * @return JourneeCaisses
     */
    public function setTransfertRecus($transfertRecus)
    {
        $this->transfertRecus = $transfertRecus;
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
     * @param mixed $intercaisseSortant
     * @return JourneeCaisses
     */
    public function setIntercaisseSortants($intercaisseSortant)
    {
        $this->intercaisseSortants = $intercaisseSortant;
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
     * @param mixed $intercaisseEntrant
     * @return JourneeCaisses
     */
    public function setIntercaisseEntrants($intercaisseEntrant)
    {
        $this->intercaisseEntrants = $intercaisseEntrant;
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
        //dump($mLiquiditeFerm);
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
        //$this->mIntercaisses = $this->mIntercaisseEntrants - $this->mIntercaisseSortants;
        return $this->mIntercaisses ;
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

    /**
     * @return mixed
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * @param mixed $transactions
     * @return JourneeCaisses
     */
    public function setTransactions($transactions)
    {
        $this->transactions = $transactions;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDetteCredits()
    {
        return $this->detteCredits;
    }

    /**
     * @param mixed $detteCredits
     * @return JourneeCaisses
     */
    public function setDetteCredits($detteCredits)
    {
        $this->detteCredits = $detteCredits;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMIntercaisseSortants()
    {
        return $this->mIntercaisseSortants;
    }

    /**
     * @param mixed $mIntercaisseSortants
     * @return JourneeCaisses
     */
    public function setMIntercaisseSortants($mIntercaisseSortants)
    {
        $this->mIntercaisseSortants = $mIntercaisseSortants;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMIntercaisseEntrants()
    {
        return $this->mIntercaisseEntrants;
    }

    /**
     * @param mixed $mIntercaisseEntrants
     * @return JourneeCaisses
     */
    public function setMIntercaisseEntrants($mIntercaisseEntrants)
    {
        $this->mIntercaisseEntrants = $mIntercaisseEntrants;
        return $this;
    }



    public function getTotalDepot(){
        $total=0;
        foreach ($this->transactions as $tr)
            foreach ($tr->getTransactionComptes() as $depot)
                if ($depot->getCompte()->getTypeCompte()=='o' || $depot->getCompte()->getTypeCompte()=='s')
                    $total=$total+$depot->getMCredit();
        return $total;
    }

    public function getTotalRetrait(){
        $total=0;
        foreach ($this->transactions as $tr)
            foreach ($tr->getTransactionComptes() as $retrait)
                if ($retrait->getCompte()->getTypeCompte()=='o' || $retrait->getCompte()->getTypeCompte()=='s')
                    $total=$total+$retrait->getMDebit();
        return $total;
    }

    public function preparerOuverture(){

        //$newJourneeCaisse=new JourneeCaisses($this->em);
        $this->setMLiquiditeOuv($this->getMLiquiditeFerm())
            ->setMSoldeElectOuv($this->getMSoldeElectFerm())
            ->setCaisse($this->getCaisse())
            ->setUtilisateur($this->getUtilisateur())
            ->setMCreditDiversOuv($this->getMCreditDiversFerm())->setMDetteDiversOuv($this->getMDetteDiversFerm())->setJourneePrecedente($this);
        //$this->getUtilisateur()->setJourneeCaisseActive($this);

        return $this;
    }

    public function getCompense()
    {
        return $this->mEmissionTrans - $this->mReceptionTrans;
    }

    /**
     * @return mixed
     */
    public function getSensTransfert()
    {
        return $this->sensTransfert;
    }

    /**
     * @param mixed $sensTransfert
     * @return JourneeCaisses
     */
    public function setSensTransfert($sensTransfert)
    {
        $this->sensTransfert = $sensTransfert;
        return $this;
    }

}