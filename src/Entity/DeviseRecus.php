<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 20/07/2018
 * Time: 17:37
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

//use App\Entity\DeviseAchatVentes;


/**
 * @ORM\Entity(repositoryClass="App\Repository\DeviseRecusRepository")
 */
class DeviseRecus
{
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
     * @ORM\Column(type="date")
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
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $paysPiece;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $motif;

    /**
     * DeviseRecus constructor.
     */
    public function __construct()
    {
        $this->deviseMouvements = new ArrayCollection();
    }


    public function setFromDeviseAchatVente(DeviseAchatVentes $deviseAchatVente)
    {
        $this->setDateRecu($deviseAchatVente->getDateRecu());
        //$this->setNomPrenom($deviseAchatVente->getNomPrenom());
        $this->setTypePiece($deviseAchatVente->getTypePiece());
        $this->setNumPiece($deviseAchatVente->getNumPiece());
        $this->setExpireLe($deviseAchatVente->getExpireLe());
        $this->setMotif($deviseAchatVente->getMotif());
        $this->setPaysPiece($deviseAchatVente->getPaysPiece());

        return $this;
    }


    public function addDeviseMouvements(DeviseMouvements $deviseMouvement)
    {
        $this->deviseMouvements->add($deviseMouvement);
        $deviseMouvement->setDeviseRecu($this);
        return $this;
    }

    public function removeDeviseMouvements(DeviseMouvements $deviseMouvement)
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


}
