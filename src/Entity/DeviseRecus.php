<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 20/07/2018
 * Time: 17:37
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping as ORM;

//use App\Entity\DeviseAchatVentes;


/**
 * @ORM\Entity(repositoryClass="App\Repository\DeviseRecusRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class DeviseRecus
{
    //const ACHAT='BORDEREAU D\'ACHAT', VENTE='BORDEREAU DE VENTE';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DeviseMouvements", mappedBy="deviseRecu", cascade={"persist"})
     */
    private $deviseMouvements;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateRecu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $typePiece;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $numPiece;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $expireLe;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pays")
     * @ORM\JoinColumn(nullable=true)
     */
    private $paysPiece;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $motif;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="string", length=1, nullable=false)
     */
    private $sens;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses" , inversedBy="deviseRecus", cascade={"persist"})
     * @ORM\JoinColumn(name="journeeCaisse", referencedColumnName="id", nullable=false)
     */
    private $journeeCaisse;

    private $em;

    private $cvdRecu=0;


    /**
     * DeviseRecus constructor.
     * @param JourneeCaisses $journeeCaisse
     * @param ObjectManager $manager
     */
    public function __construct(JourneeCaisses $journeeCaisse, ObjectManager $manager)
    {
        $this->journeeCaisse=$journeeCaisse;
        $this->em=$manager;
        $this->deviseMouvements = new ArrayCollection();
        $this->dateRecu=new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateNature(){

        //$this->setNature();
    }

    /**
     * @ORM\PrePersist
     */
    public function setNature(){
        //($this->sens == DeviseMouvements::ACHAT)?$this->nature=$this::ACHAT:$this->nature=$this::VENTE;
    }


    /**
     * @param DeviseMouvements $deviseMouvement
     * @return $this
     */
    public function addDeviseMouvement(DeviseMouvements $deviseMouvement)
    {


        $deviseMouvement->setSens($this->getSens())
            ->setDeviseRecu($this);

        $this->setComment($this->getComment().' | '.$deviseMouvement->getNombre().' '.$deviseMouvement->getDevise().' = '.$deviseMouvement->getContreValeur());

        $this->updateCvd($deviseMouvement->getContreValeur());
        $this->deviseMouvements->add($deviseMouvement);
        $deviseMouvement->setDeviseJourneeByJourneeCaisse($this->journeeCaisse, $this->em)
            ->setSoldeOuvByDeviseAndCaisse($deviseMouvement, $this->journeeCaisse, $this->em);
        //dump($deviseMouvement); die();
        return $this;
    }

    /**
     * @param DeviseMouvements $deviseMouvement
     * @return $this
     */
    public function removeDeviseMouvement(DeviseMouvements $deviseMouvement)
    {
        $this->deviseMouvements->removeElement($deviseMouvement);
        return $this;
    }


    public function getId()
    {
        return $this->id;
    }

    public function getDateRecu()
    {
        return $this->dateRecu;
    }

    public function setDateRecu(\DateTime $dateRecu)
    {
        $this->dateRecu = $dateRecu;

        return $this;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom(String $nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     * @return DeviseRecus
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getTypePiece()
    {
        return $this->typePiece;
    }

    public function setTypePiece(String $typePiece)
    {
        $this->typePiece = $typePiece;

        return $this;
    }

    public function getNumPiece()
    {
        return $this->numPiece;
    }

    public function setNumPiece(String $numPiece)
    {
        $this->numPiece = $numPiece;

        return $this;
    }

    public function getExpireLe()
    {
        return $this->expireLe;
    }

    public function setExpireLe(\DateTime $expireLe)
    {
        $this->expireLe = $expireLe;

        return $this;
    }

    public function getMotif()
    {
        return $this->motif;
    }

    public function setMotif(String $motif)
    {
        $this->motif = $motif;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaysPiece()
    {
        return $this->paysPiece;
    }

    /**
     * @param mixed $paysPiece
     * @return DeviseRecus
     */
    public function setPaysPiece($paysPiece)
    {
        $this->paysPiece = $paysPiece;
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
     * @return DeviseRecus
    */
    public function setDeviseMouvements($deviseMouvements)
    {
        $this->deviseMouvements = $deviseMouvements;
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
     * @return DeviseRecus
     */
    public function setJourneeCaisse($journeeCaisse)
    {
        $this->journeeCaisse = $journeeCaisse;
        return $this;
    }
    
    /**
     * @return ObjectManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param ObjectManager $em
     * @return DeviseRecus
     */
    public function setEm($em)
    {
        $this->em = $em;
        return $this;
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
     * @return DeviseRecus
     */
    public function setSens($sens)
    {
        $this->sens = $sens;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        $total=0;
        foreach ($this->getDeviseMouvements() as $mvt){
            $total+=$mvt->getContreValeur();
        }
        return $total;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     * @return DeviseRecus
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return int
     */
    public function getCvdRecu()
    {
        return $this->cvdRecu;
    }

    /**
     * @param int $cvdRecu
     * @return DeviseRecus
     */
    public function setCvdRecu($cvdRecu)
    {
        $this->cvdRecu = $cvdRecu;
        return $this;
    }

    private function updateCvd($montant){
        $this->cvdRecu+=$montant;
    }
}
