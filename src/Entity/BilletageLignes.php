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
     * @ORM\ManyToOne(targetEntity="App\Entity\Billetages", inversedBy="billetageLignes", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $idBilletage;

    /**
     * @ORM\Column(type="float")
     */
    private $valeurBillet=0;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbBillet=0;

    /**
     * @ORM\Column(type="float")
     */
    private $valeurLigne=0;

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
     * @param $idBilletage
     * @return $this
     */
    public function setIdBilletage($idBilletage)
    {
        $this->idBilletage = $idBilletage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValeurBillet()
    {
        return $this->valeurBillet;
    }

    /**
     * @param $valeurBillet
     * @return $this
     */
    public function setValeurBillet($valeurBillet)
    {
        $this->valeurBillet = $valeurBillet;
        //($this->nbBillet !=0)?$this->valeurLigne=$valeurBillet*$this->nbBillet:$this->valeurLigne=0;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNbBillet()
    {
        return $this->nbBillet;
    }

    /**
     * @param $nbBillet
     * @return $this
     */
    public function setNbBillet($nbBillet)
    {
        $this->nbBillet = $nbBillet;
        //($this->valeurBillet !=0)?$this->valeurLigne=$nbBillet*$this->valeurBillet:$this->valeurLigne=0;
        return $this;
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