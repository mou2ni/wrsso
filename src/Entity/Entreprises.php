<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 30/01/2019
 * Time: 12:22
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntreprisesRepository")
 */
class Entreprises
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
    private $code;

    /**
     * @ORM\Column(type="string")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $adresse;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Agences", mappedBy="entreprise", cascade={"persist"})
     */
    private $agences;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Collaborateurs", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $representant;

    /**
     * Entreprises constructor.
     * @internal param $agences
     */
    public function __construct()
    {
        $this->agences = new ArrayCollection();
    }




    public function __toString()
    {
        return $this->getCode();
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
     * @return Entreprises
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
     * @return Entreprises
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
     * @return Entreprises
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
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
     * @return Entreprises
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRepresentant()
    {
        return $this->representant;
    }

    /**
     * @param mixed $representant
     * @return Entreprises
     */
    public function setRepresentant($representant)
    {
        $this->representant = $representant;
        return $this;
    }

    public function addAgence(Agences $agence)
    {
        $this->agences->add($agence);
        $agence->setEntreprise($this);
    }

    public function removeCollaborateur(Agences $agence)
    {
        $this->agences->removeElement($agence);
    }

    /**
     * @return mixed
     */
    public function getAgences()
    {
        return $this->agences;
    }

    /**
     * @param mixed $agences
     * @return Entreprises
     */
    public function setAgences($agences)
    {
        $this->agences = $agences;
        return $this;
    }
}