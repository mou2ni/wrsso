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
 * @ORM\Table(name="DeviseMouvements")
 * @ORM\HasLifecycleCallbacks()
 */
class DeviseMouvements
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idJourneeCaisse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devises")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idDevise;

    /**
     * @ORM\Column(type="string")
     */
    private $sens;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombre;

    /**
     * @ORM\Column(type="integer")
     */
    private $mCvd;

    /**
     * @ORM\Column(type="float")
     */
    private $taux;


    /**
     * @ORM\PreUpdate
     */
    public function updateMCvd(){
        if ($this->getSens()=='v') $this->setMCvd($this->getNombre()*$this->getTaux());
        else $this->setMCvd(-$this->getNombre()*$this->getTaux());
    }

    /**
     * @ORM\PrePersist
     */
    public function createMCvd(){
        $this->updateMCvd();
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
    }

    /**
     * @return mixed
     */
    public function getIdJourneeCaisse()
    {
        return $this->idJourneeCaisse;
    }

    /**
     * @param mixed $idJourneeCaisse
     */
    public function setIdJourneeCaisse($idJourneeCaisse)
    {
        $this->idJourneeCaisse = $idJourneeCaisse;
    }

    /**
     * @return mixed
     */
    public function getIdDevise()
    {
        return $this->idDevise;
    }

    /**
     * @param mixed $idDevise
     */
    public function setIdDevise($idDevise)
    {
        $this->idDevise = $idDevise;
    }

    /**
     * @return mixed
     */
    public function getSens()
    {
        return $this->sens;
    }

    /**
     * @param mixed $sens
     */
    public function setSens($sens)
    {
        $this->sens = $sens;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getMCvd()
    {
        return $this->mCvd;
    }

    /**
     * @param mixed $mCvd
     */
    public function setMCvd($mCvd)
    {
        $this->mCvd = $mCvd;
    }

    /**
     * @return mixed
     */
    public function getTaux()
    {
        return $this->taux;
    }

    /**
     * @param mixed $taux
     * @return DeviseMouvements
     */
    public function setTaux($taux)
    {
        $this->taux = $taux;
        return $this;
    }



   }