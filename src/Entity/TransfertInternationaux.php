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
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity (repositoryClass="App\Repository\TransfertInternationauxRepository")
 * @ORM\Table(name="TransfertInternationaux")
 * @ORM\HasLifecycleCallbacks()
 */
class TransfertInternationaux
{
    const ERR_NEGATIF=1, ERR_ZERO=0;
    private $e ;
    const TVA=0.18, TTZ=0.006;
    const ENVOI=1, RECEPTION=2, REMBOURSEMENT=3;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses" , inversedBy="transfertInternationaux")
     * @ORM\JoinColumn(name="journeeCaisse", referencedColumnName="id", nullable=false)
     */
    private $journeeCaisse;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses" , inversedBy="transfertEmis")
     * @ORM\JoinColumn(name="journeeCaisseEmi", referencedColumnName="id", nullable=true)
     */
    private $journeeCaisseEmi;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses" , inversedBy="transfertRecus")
     * @ORM\JoinColumn(name="journeeCaisseRecu", referencedColumnName="id", nullable=true)
     */
    private $journeeCaisseRecu;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Transactions")
     * @ORM\JoinColumn(nullable=true)
     */
    private $transaction;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compenses", inversedBy="transfertInternationaux")
     * @ORM\JoinColumn(nullable=true)
     */
    private $compense;

    /**
     * @ORM\Column(type="string")
     */
    private $sens;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateTransfert;

    /**
     * @ORM\Column(type="float")
     * @Assert\GreaterThan(value="0")
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
     * @Assert\GreaterThan(value="0")
     */
    private $mTransfertTTC=0;

    //private $tva;
    //private $ttz;

   
    public function __construct()
    {
        //$this->ttz = $container->getParameter('ttz');
        //$this->tva = $container->getParameter('tva');
        $this->mFraisHt = 0;
        $this->mTva = 0;
        $this->mAutresTaxes = 0;
        $this->mTransfertTTC = 0;
        $this->dateTransfert=new \DateTime();
        //$this->container=$container;
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
        $this->setMAutresTaxes()->setMFraisHt()->setMTva();
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

    /*
     * @return mixed
    
    public function getIdJourneeCaisse()
    {
        return $this->idJourneeCaisse;
    } */

    /*
     * @param mixed $idJourneeCaisse
     
    public function setIdJourneeCaisse($idJourneeCaisse)
    {
        $this->idJourneeCaisse = $idJourneeCaisse;
    }
*/
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
        if ($this->getSens()==$this::ENVOI)
            return ($this->getMFraisTTC() - $this->mAutresTaxes) / (1 + $this::TVA);
        else
            return 0;
    }
    /**
     * @return mixed
     */
    public function getMFraisTTC()
    {
        return $this->getMTransfertTTC() - $this->getMTransfert();
    }

    /**
     * @param mixed $mFraisHt
     */
    public function setMFraisHt()
    {

        $this->mFraisHt = $this->getMFraisHt();
       return $this;
    }

    /**
     * @return mixed
     */
    public function getMTva()
    {
        $this->mTva =  $this->getMFraisTTC() - $this->mAutresTaxes - $this->mFraisHt;
        if ($this->getSens()==$this::ENVOI)
            return $this->getMFraisTTC() - $this->getMAutresTaxes() - $this->getMFraisHt();
        else
            return 0;
    }

    /**
     * @param mixed $mTva
     */
    public function setMTva()
    {
        $this->mTva = $this->getMTva();
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMAutresTaxes()
    {
        if ($this->getIdPays()->getDansRegion())
            return 0;
        elseif ($this->getMFraisHt()!=0 and $this->getSens()== TransfertInternationaux::ENVOI)
            return $this::TTZ*$this->getMTransfert();
        else return 0;
    }

    /**
     * @param mixed $mAutresTaxes
     */
    public function setMAutresTaxes()
    {
        /*$autresTaxes = $this->mTransfertTTC-$this->mTransfert-$this->mFraisHt-$this->mTva;
        $this->mAutresTaxes = $autresTaxes>0?$autresTaxes:0;*/
        $this->mAutresTaxes = $this->getMAutresTaxes();
        return $this;
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

    /**
     * @return mixed
     */
    public function getJourneeCaisse()
    {
        return $this->journeeCaisse;
    }

    /**
     * @param mixed $journeeCaisse
     * @return TransfertInternationaux
     */
    public function setJourneeCaisse($journeeCaisse)
    {
        $this->journeeCaisse = $journeeCaisse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJourneeCaisseEmi()
    {
        return $this->journeeCaisseEmi;
    }

    /**
     * @param mixed $journeeCaisseEmi
     * @return TransfertInternationaux
     */
    public function setJourneeCaisseEmi($journeeCaisseEmi)
    {
        $this->journeeCaisseEmi = $journeeCaisseEmi;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJourneeCaisseRecu()
    {
        return $this->journeeCaisseRecu;
    }

    /**
     * @param mixed $journeeCaisseRecu
     * @return TransfertInternationaux
     */
    public function setJourneeCaisseRecu($journeeCaisseRecu)
    {
        $this->journeeCaisseRecu = $journeeCaisseRecu;
        return $this;
    }

    /**
     * @return Transactions
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param mixed $transaction
     * @return TransfertInternationaux
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateTransfert()
    {
        return $this->dateTransfert;
    }

    /**
     * @param \DateTime $dateTransfert
     * @return TransfertInternationaux
     */
    public function setDateTransfert($dateTransfert)
    {
        $this->dateTransfert = $dateTransfert;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompense()
    {
        return $this->compense;
    }

    /**
     * @param mixed $compense
     * @return TransfertInternationaux
     */
    public function setCompense($compense)
    {
        $this->compense = $compense;
        return $this;
    }
}
