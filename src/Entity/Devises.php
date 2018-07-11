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
 * @ORM\Table(name="Devises")
 */
class Devises
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
    private $code;

    /**
     * @ORM\Column(type="string")
     */
    private $libelle;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateModification;

    /**
     * @ORM\Column(type="float")
     */
    private $txAchat;

    /**
     * @ORM\Column(type="float")
     */
    private $txVente;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Devises
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return Devises
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
     * @return Devises
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * @param mixed $dateModification
     * @return Devises
     */
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTxAchat()
    {
        return $this->txAchat;
    }

    /**
     * @param mixed $txAchat
     * @return Devises
     */
    public function setTxAchat($txAchat)
    {
        $this->txAchat = $txAchat;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTxVente()
    {
        return $this->txVente;
    }

    /**
     * @param mixed $txVente
     * @return Devises
     */
    public function setTxVente($txVente)
    {
        $this->txVente = $txVente;
        return $this;
    }



    public function __toString()
    {
        return ''.$this->getCode();
    }


}