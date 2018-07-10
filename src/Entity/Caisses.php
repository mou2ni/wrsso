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
 * @ORM\Table(name="Caisses")
 */
class Caisses
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
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idCompteOperation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idCompteEcart;

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
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdCompteOperation()
    {
        return $this->idCompteOperation;
    }

    /**
     * @param mixed $idCompteOperation
     */
    public function setIdCompteOperation($idCompteOperation)
    {
        $this->idCompteOperation = $idCompteOperation;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdCompteEcart()
    {
        return $this->idCompteEcart;
    }

    /**
     * @param mixed $idCompteEcart
     */
    public function setIdCompteEcart($idCompteEcart)
    {
        $this->idCompteEcart = $idCompteEcart;
        return $this;
    }

    public function __toString()
    {
        return ''.$this->getLibelle();
    }
   }