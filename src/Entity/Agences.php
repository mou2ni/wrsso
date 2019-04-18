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
 * @ORM\Entity(repositoryClass="App\Repository\AgencesRepository")
 */
class Agences
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $code;

   /**
    * @ORM\Column(type="string")
    */
    private $libelle;

    /**
     * @ORM\Column(type="string")
     */
    private $adresse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entreprises", inversedBy="agences", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $entreprise;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Collaborateurs", mappedBy="agence", cascade={"persist"})
     */
    private $collaborateurs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Caisses", mappedBy="agence", cascade={"persist"})
     */
    private $caisses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LigneSalaires", mappedBy="agence", cascade={"persist"})
     */
    private $ligneSalaires;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecetteDepenses", mappedBy="agence", cascade={"persist"})
     */
    private $recetteDepenses;

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getCode();
    }

    public function getDisplayName(){
        return $this->getCode().' - '.$this->getLibelle();
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
     * @return Agences
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     * @return Agences
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param mixed $libelle
     * @return Agences
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntreprise()
    {
        return $this->entreprise;
    }

    /**
     * @param mixed $entreprise
     * @return Agences
     */
    public function setEntreprise($entreprise)
    {
        $this->entreprise = $entreprise;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCollaborateurs()
    {
        return $this->collaborateurs;
    }

    /**
     * @param mixed $collaborateurs
     * @return Agences
     */
    public function setCollaborateurs($collaborateurs)
    {
        $this->collaborateurs = $collaborateurs;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCaisses()
    {
        return $this->caisses;
    }

    /**
     * @param mixed $caisses
     * @return Agences
     */
    public function setCaisses($caisses)
    {
        $this->caisses = $caisses;
        return $this;
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
     * @return Agences
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecetteDepenses()
    {
        return $this->recetteDepenses;
    }

    /**
     * @param mixed $recetteDepenses
     * @return Agences
     */
    public function setRecetteDepenses($recetteDepenses)
    {
        $this->recetteDepenses = $recetteDepenses;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLigneSalaires()
    {
        return $this->ligneSalaires;
    }

    /**
     * @param mixed $ligneSalaires
     * @return Agences
     */
    public function setLigneSalaires($ligneSalaires)
    {
        $this->ligneSalaires = $ligneSalaires;
        return $this;
    }
}