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
 * @ORM\Entity(repositoryClass="App\Repository\RecetteDepenseAgencesRepository")
 */
class RecetteDepenseAgences
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecetteDepenses", inversedBy="recetteDepenseAgences", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $recetteDepense;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agences", inversedBy="recetteDepenseAgences", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $agence;

    /**
     * @ORM\Column(type="float")
     */
    private $mPart=0;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return RecetteDepenseAgences
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecetteDepense()
    {
        return $this->recetteDepense;
    }

    /**
     * @param mixed $recetteDepense
     * @return RecetteDepenseAgences
     */
    public function setRecetteDepense($recetteDepense)
    {
        $this->recetteDepense = $recetteDepense;
        return $this;
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
     * @return RecetteDepenseAgences
     */
    public function setAgence($agence)
    {
        $this->agence = $agence;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMPart()
    {
        return $this->mPart;
    }

    /**
     * @param mixed $mPart
     * @return RecetteDepenseAgences
     */
    public function setMPart($mPart)
    {
        $this->mPart = $mPart;
        return $this;
    }



}