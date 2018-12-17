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
use Serializable;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="Utilisateurs")
 */
class Utilisateurs implements UserInterface, \Serializable
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
     * @ORM\OneToMany(targetEntity="App\Entity\JourneeCaisses", mappedBy="utilisateur", cascade={"persist"})
     * @ORM\JoinColumn()
     */
    private $journeeCaisses;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses" ,cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $journeeCaisseActive;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $journeeCaisseActiveId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Caisses", inversedBy="utilisateurs", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $lastCaisse;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\DetteCreditDivers" ,cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $detteCreditCrees;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\DetteCreditDivers" ,cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $detteCreditRembourses;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $role;

    private $isAuthaticate;



    /**
     * Utilisateurs constructor.
     * @param $journeeCaisses
     */
    public function __construct()
    {
        $this->journeeCaisses = new ArrayCollection();
        //$this->compteEcartCaisse=new Comptes();
        $this->role='ROLE_USER';
    }

    /*
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes" , inversedBy="utilisateurCompteEcarts", cascade={"persist"})
     * @ORM\JoinColumn(name="id_cpt_compense", referencedColumnName="id", nullable=false)

    private $compteCompense;*/

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role = null)
    {
        $this->role = $role;
    }

    public function getRoles()
    {
        return [$this->getRole()];
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
        //$this->mdp =hash('SHA1',''.$mdp);
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

    /**
     * @return mixed
     */
    public function getJourneeCaisseActive()
    {
        return $this->journeeCaisseActive;
    }

    /**
     * @param mixed $journeeCaisseActive
     * @return Utilisateurs
     */
    public function setJourneeCaisseActive(JourneeCaisses $journeeCaisseActive)
    {
        $this->journeeCaisseActive = $journeeCaisseActive;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJourneeCaisseActiveId()
    {
        return $this->journeeCaisseActiveId;
    }

    /**
     * @param mixed $journeeCaisseActiveId
     * @return Utilisateurs
     */
    public function setJourneeCaisseActiveId($journeeCaisseActiveId)
    {
        $this->journeeCaisseActiveId = $journeeCaisseActiveId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDetteCreditCrees()
    {
        return $this->detteCreditCrees;
    }

    /**
     * @param mixed $detteCreditCrees
     * @return Utilisateurs
     */
    public function setDetteCreditCrees($detteCreditCrees)
    {
        $this->detteCreditCrees = $detteCreditCrees;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDetteCreditRembourses()
    {
        return $this->detteCreditRembourses;
    }

    /**
     * @param mixed $detteCreditRembourses
     * @return Utilisateurs
     */
    public function setDetteCreditRembourses($detteCreditRembourses)
    {
        $this->detteCreditRembourses = $detteCreditRembourses;
        return $this;
    }


    /**
     * @return Caisses
     */
    public function getLastCaisse()
    {
        return $this->lastCaisse;
    }

    /**
     * @param mixed $lastCaisse
     * @return Utilisateurs
     */
    public function setLastCaisse($lastCaisse)
    {
        $this->lastCaisse = $lastCaisse;
        return $this;
    }

    public function updateJourneeCaisse(JourneeCaisses $journeeCaisse){
        $this->journeeCaisseActive = $journeeCaisse;
        $this->lastCaisse = $journeeCaisse->getCaisse();
    }




    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->mdp;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->login;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function serialize() {
        return serialize(array(
            $this->id,
            $this->login,
            $this->nom,
            $this->prenom,
            $this->mdp,
            $this->estCaissier,
            $this->journeeCaisseActive,
        ));
    }

    public function unserialize($serialized) {
        list (
            $this->id,
            $this->login,
            $this->nom,
            $this->prenom,
            $this->mdp,
            $this->estCaissier,
            $this->journeeCaisseActive,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }
}