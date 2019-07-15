<?php
/**
 * Created by PhpStorm.
 * User: Hamado
 * Date: 25/04/2019
 * Time: 14:44
 */

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RubriqueAnalysesRepository")
 */
class RubriqueAnalyses
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecetteDepenseAgences", mappedBy="rubriqueAnalyse", cascade={"persist"})
     */
    private $recetteDepenseAgences;

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string")
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRecette=true;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return RubriqueAnalyses
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecetteDepenseAgences()
    {
        return $this->recetteDepenseAgences;
    }

    /**
     * @param mixed $recetteDepenseAgences
     * @return RubriqueAnalyses
     */
    public function setRecetteDepenseAgences($recetteDepenseAgences)
    {
        $this->recetteDepenseAgences = $recetteDepenseAgences;
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
     * @return RubriqueAnalyses
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
     * @return RubriqueAnalyses
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsRecette()
    {
        return $this->isRecette;
    }

    /**
     * @param mixed $isRecette
     * @return RubriqueAnalyses
     */
    public function setIsRecette($isRecette)
    {
        $this->isRecette = $isRecette;
        return $this;
    }



}