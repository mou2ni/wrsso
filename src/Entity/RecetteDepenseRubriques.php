<?php
/**
 * Created by PhpStorm.
 * User: Hamado
 * Date: 25/04/2019
 * Time: 14:55
 */

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecetteDepenseRubriquesRepository")
 */
class RecetteDepenseRubriques
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecetteDepenses", inversedBy="recetteDepenseRubriques", cascade={"persist"})
     */
    private $recetteDepense;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RubriqueAnalyses", inversedBy="recetteDepenseAgences", cascade={"persist"})
     */
    private $rubriqueAnalyse;

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
     * @return RecetteDepenseRubriques
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
     * @return RecetteDepenseRubriques
     */
    public function setRecetteDepense($recetteDepense)
    {
        $this->recetteDepense = $recetteDepense;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRubriqueAnalyse()
    {
        return $this->rubriqueAnalyse;
    }

    /**
     * @param mixed $rubriqueAnalyse
     * @return RecetteDepenseRubriques
     */
    public function setRubriqueAnalyse($rubriqueAnalyse)
    {
        $this->rubriqueAnalyse = $rubriqueAnalyse;
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
     * @return RecetteDepenseRubriques
     */
    public function setMPart($mPart)
    {
        $this->mPart = $mPart;
        return $this;
    }
    


}