<?php
/**
 * Created by PhpStorm.
 * User: Mouni
 * Date: 22/11/2016
 * Time: 10:39
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientsRepository")
 * @ORM\Table(name="Clients")
 */
class Clients
{
    const TYP_CLIENT='CLT', TYP_FOURNISSEUR='FRS', TYP_PERSONNEL='PER', TYP_DIVERS='DIV', TYP_INTERNE='INT';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $nom;

    /**
     * @ORM\Column(type="string")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $typeTier=Clients::TYP_CLIENT;



    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comptes", mappedBy="client", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $comptes;


    public function __toString()
    {
        return $this->getPrenom().' '.$this->getNom();
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
     * @return Clients
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
     * @return Clients
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
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
     * @return Clients
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComptes()
    {
        return $this->comptes;
    }

    /**
     * @param mixed $comptes
     * @return Clients
     */
    public function setComptes($comptes)
    {
        $this->comptes = $comptes;
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
     * @return Clients
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQualite()
    {
        return $this->qualite;
    }

    /**
     * @param mixed $qualite
     * @return Clients
     */
    public function setQualite($qualite)
    {
        $this->qualite = $qualite;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEstRepresentant()
    {
        return $this->estRepresentant;
    }

    /**
     * @param mixed $estRepresentant
     * @return Clients
     */
    public function setEstRepresentant($estRepresentant)
    {
        $this->estRepresentant = $estRepresentant;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTypeTier()
    {
        return $this->typeTier;
    }

    /**
     * @param mixed $typeTier
     * @return Clients
     */
    public function setTypeTier($typeTier)
    {
        $this->typeTier = $typeTier;
        return $this;
    }



   


}