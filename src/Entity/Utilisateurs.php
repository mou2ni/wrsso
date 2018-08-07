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
 * @ORM\Entity()
 * @ORM\Table(name="Utilisateurs")
 */
class Utilisateurs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $login;

    /**
     * @ORM\Column(type="string")
     */
    private $mdp;
    /**
     * @ORM\Column(type="string")
     */
    private $nom;

    /**
     * @ORM\Column(type="string")
     */
    private $prenom;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estCaissier;

    /**
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes" , inversedBy="utilisateurCompteEcarts", cascade={"persist"})
     * @ORM\JoinColumn(name="id_cpt_ecart", referencedColumnName="id", nullable=false)
     */
    private $compteEcartCaisse;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\JourneeCaisses", mappedBy="idUtilisateur", cascade={"persist"})
     */
    private $journeeCaisses;

    private $isAuthaticate;

    /**
     * Utilisateurs constructor.
     * @param $journeeCaisses
     */
    public function __construct()
    {
        $this->journeeCaisses = new ArrayCollection();
    }

    /*
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes" , inversedBy="utilisateurCompteEcarts", cascade={"persist"})
     * @ORM\JoinColumn(name="id_cpt_compense", referencedColumnName="id", nullable=false)

    private $compteCompense;*/

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
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     * @return Utilisateurs
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMdp()
    {
        return $this->mdp;
    }

    /**
     * @param mixed $mdp
     * @return Utilisateurs
     */
    public function setMdp($mdp)
    {
        $this->mdp = $mdp;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
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
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEstCaissier()
    {
        return $this->estCaissier;
    }

    /**
     * @param mixed $estCaissier
     */
    public function setEstCaissier($estCaissier)
    {
        $this->estCaissier = $estCaissier;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function __toString()
    {
        return ''.$this->getNom().' '.$this->getPrenom();
    }

    public function estActif(){
        if ($this->getStatus()=='A') return true;
        else return false;
    }

    /**
     * @return mixed
     */
    public function getCompteEcartCaisse()
    {
        return $this->compteEcartCaisse;
    }

    /**
     * @param mixed $compteEcartCaisse
     * @return Utilisateurs
     */
    public function setCompteEcartCaisse($compteEcartCaisse)
    {
        $this->compteEcartCaisse = $compteEcartCaisse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJourneeCaisses()
    {
        return $this->journeeCaisses;
    }

    /**
     * @param mixed $journeeCaisses
     * @return Utilisateurs
     */
    public function setJourneeCaisses($journeeCaisses)
    {
        $this->journeeCaisses = $journeeCaisses;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getisAuthaticate()
    {
        return $this->isAuthaticate;
    }

    /**
     * @param mixed $isAuthaticate
     * @return Utilisateurs
     */
    public function setIsAuthaticate($isAuthaticate)
    {
        $this->isAuthaticate = $isAuthaticate;
        return $this;
    }



}