<?php
/**
 * Created by Hamado.
 * User: Hamado, OUEDRAOGO
 * Date: 21/03/2019
 * Time: 14:39
 */

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Callback;

/**
 * @ORM\Entity (repositoryClass="App\Repository\CompensesRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Compenses
{
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateurs")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transactions")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $transaction;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses", inversedBy="compenses", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $journeeCaisse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Caisses")
     * @ORM\JoinColumn(nullable=true)
     */
    private $caisse;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CompenseLignes", mappedBy="compense", cascade={"persist"})
     */
    private $compenseLignes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TransfertInternationaux", mappedBy="compense", cascade={"persist"})
     */
    private $transfertInternationaux;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $dateFin;

    /**
     * @ORM\Column(type="float")
     */
    private $totalEnvoi=0;

    /**
     * @ORM\Column(type="float")
     */
    private $totalReception=0;

    public function __construct()
    {
        $this->compenseLignes=new ArrayCollection();
        $this->dateDebut=new \DateTime();
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
     * @return Compenses
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Caisses
     */
    public function getCaisse()
    {
        return $this->caisse;
    }

    /**
     * @param Caisses $caisse
     * @return Compenses
     */
    public function setCaisse($caisse)
    {
        $this->caisse = $caisse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompenseLignes()
    {
        return $this->compenseLignes;
    }

    /**
    * @param mixed $compenseLignes
    * @return $this
    */
    public function setCompenseLignes($compenseLignes)
    {
        $this->compenseLignes=$compenseLignes;
        return $this;
    }

    /**
     * @param CompenseLignes $compenseLigne
     * @return $this
     */
    public function addCompenseLigne(CompenseLignes $compenseLigne)
    {
        $this->compenseLignes->add($compenseLigne);
        $compenseLigne->setCompense($this);
        return $this;
    }

    /**
     * @param CompenseLignes $compenseLigne
     * @return $this
     */
    public function removeLigneSalaire(CompenseLignes $compenseLigne)
    {
        $this->compenseLignes->removeElement($compenseLigne);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * @param mixed $dateDebut
     * @return Compenses
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * @param mixed $dateFin
     * @return Compenses
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getTotalEnvoi()
    {
        return $this->totalEnvoi;
    }

    /**
     * @param mixed $totalEnvoi
     * @return Compenses
     */
    public function setTotalEnvoi($totalEnvoi)
    {
        $this->totalEnvoi = $totalEnvoi;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalReception()
    {
        return $this->totalReception;
    }

    /**
     * @param mixed $totalReception
     * @return Compenses
     */
    public function setTotalReception($totalReception)
    {
        $this->totalReception = $totalReception;
        return $this;
    }

    public function maintenirTotaux(){
        $totalReception=0;
        $totalEnvoi=0;
        foreach ($this->getCompenseLignes() as $compenseLigne){
            $totalEnvoi=$totalEnvoi+$compenseLigne->getMEnvoiCompense();
            $totalReception=$totalReception+$compenseLigne->getMReceptionCompense();
        }
        $this->setTotalEnvoi($totalEnvoi);
        $this->setTotalReception($totalReception);
    }

    /**
     * @return mixed
     */
    public function getTransfertInternationaux()
    {
        return $this->transfertInternationaux;
    }

    /**
     * @param mixed $transfertInternationaux
     * @return Compenses
     */
    public function setTransfertInternationaux($transfertInternationaux)
    {
        $this->transfertInternationaux = $transfertInternationaux;
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
     * @return Compenses
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
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
     * @return Compenses
     */
    public function setJourneeCaisse($journeeCaisse)
    {
        $this->journeeCaisse = $journeeCaisse;
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
     * @return Compenses
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }
    
    public function getSoldeCompense(){
        return $this->getTotalEnvoi()-$this->getTotalReception();
    }
    
    public function maintenirCompenseAttendue(){
        
    }

}
