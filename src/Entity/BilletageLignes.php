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
 * @ORM\Entity
 * @ORM\Table(name="BilletageLignes")
 */
class BilletageLignes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Billetages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idBilletage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Billets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $valeurBillet;

    /**
     * @ORM\Column(type="float")
     */
    private $nbBillet;

    /**
     * @ORM\Column(type="float")
     */
    private $valeurLigne;

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
    public function getIdBilletage()
    {
        return $this->idBilletage;
    }

    /**
     * @param mixed $idBilletage
     */
    public function setIdBilletage($idBilletage)
    {
        $this->idBilletage = $idBilletage;
    }

    /**
     * @return mixed
     */
    public function getValeurBillet()
    {
        return $this->valeurBillet;
    }

    /**
     * @param mixed $valeurBillet
     */
    public function setValeurBillet($valeurBillet)
    {
        $this->valeurBillet = $valeurBillet;
    }

    /**
     * @return mixed
     */
    public function getNbBillet()
    {
        return $this->nbBillet;
    }

    /**
     * @param mixed $nbBillet
     */
    public function setNbBillet($nbBillet)
    {
        $this->nbBillet = $nbBillet;
    }

    /**
     * @return mixed
     */
    public function getValeurLigne()
    {
        return $this->valeurLigne;
    }

    /**
     * @param mixed $valeurLigne
     */
    public function setValeurLigne($valeurLigne)
    {
        $this->valeurLigne = $valeurLigne;
    }



}