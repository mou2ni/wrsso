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
 * @ORM\Entity(repositoryClass="App\Repository\RapportGestionAgencesRepository")
 */
class RapportGestionAgences
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
    private $agence;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDebutRapport;

    /**
     * @ORM\Column(type="date")
     */
    private $dateFinRapport;

    /**
     * @ORM\Column(type="float")
     */
    private $mRecette=0;

    /**
     * @ORM\Column(type="float")
     */
    private $mDepense=0;

    /**
     * @ORM\Column(type="float")
     */
    private $mCoutSalaire=0;

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
    public function getAgence()
    {
        return $this->agence;
    }

    /**
     * @param mixed $agence
     */
    public function setAgence($agence)
    {
        $this->agence = $agence;
    }

    /**
     * @return mixed
     */
    public function getDateDebutRapport()
    {
        return $this->dateDebutRapport;
    }

    /**
     * @param mixed $dateDebutRapport
     * @return RapportGestionAgences
     */
    public function setDateDebutRapport($dateDebutRapport)
    {
        $this->dateDebutRapport = $dateDebutRapport;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateFinRapport()
    {
        return $this->dateFinRapport;
    }

    /**
     * @param mixed $dateFinRapport
     * @return RapportGestionAgences
     */
    public function setDateFinRapport($dateFinRapport)
    {
        $this->dateFinRapport = $dateFinRapport;
        return $this;
    }
    

    /**
     * @return mixed
     */
    public function getMRecette()
    {
        return $this->mRecette;
    }

    /**
     * @param mixed $mRecette
     */
    public function setMRecette($mRecette)
    {
        $this->mRecette = $mRecette;
    }

    /**
     * @return mixed
     */
    public function getMDepense()
    {
        return $this->mDepense;
    }

    /**
     * @param mixed $mDepense
     */
    public function setMDepense($mDepense)
    {
        $this->mDepense = $mDepense;
    }

    /**
     * @return mixed
     */
    public function getMCoutSalaire()
    {
        return $this->mCoutSalaire;
    }

    /**
     * @param mixed $mCoutSalaire
     */
    public function setMCoutSalaire($mCoutSalaire)
    {
        $this->mCoutSalaire = $mCoutSalaire;
    }

    public function getVA(){
        return $this->getMRecette()-$this->getMDepense();
    }

    public function getEBE(){
        return $this->getVA()-$this->getMCoutSalaire();
    }

}