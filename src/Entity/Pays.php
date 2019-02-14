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
 * @ORM\Entity(repositoryClass="App\Repository\PaysRepository")
 * @ORM\Table(name="Pays")
 */
class Pays
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5, unique=true, nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string")
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Zones", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $zone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $dansRegion = false;

    public function __toString()
    {
        return ''.$this->getLibelle();
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
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param mixed $libelle
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * @param mixed $zone
     */
    public function setZone($zone)
    {
        $this->zone = $zone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDansRegion()
    {
        return $this->dansRegion;
    }

    /**
     * @param mixed $dansRegion
     * @return Pays
     */
    public function setDansRegion($dansRegion)
    {
        $this->dansRegion = $dansRegion;
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
     * @return Pays
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }


}