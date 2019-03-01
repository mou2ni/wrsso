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
 * @ORM\Entity(repositoryClass="App\Repository\DevisesRepository")
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
     * @ORM\Column(type="float",nullable=true)
     */
    private $txReference;

    /**
     * @ORM\Column(type="float")
     */
    private $txVente;
    /**
     * @ORM\Column(type="string")
     */
    private $formuleAchat;
    /**
     * @ORM\Column(type="string")
     */
    private $formuleVente;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Billets", mappedBy="devise", cascade={"persist"})
     */
    private $billets;

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
        $this->txVente=$txVente;
        return $this;
    }



    public function __toString()
    {
        return ''.$this->getCode();
    }

    /**
     * @return mixed
     */
    public function getBillets()
    {
        return $this->billets;
    }

    /**
     * @param mixed $billets
     * @return Devises
     */
    public function setBillets($billets)
    {
        $this->billets = $billets;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTxReference()
    {
        return $this->txReference;
    }

    /**
     * @param mixed $txReference
     * @return Devises
     */
    public function setTxReference($txReference)
    {

        $this->txReference = $txReference;
        $this->setFormuleAchat($this->formuleAchat);
        $this->setFormuleVente($this->formuleVente);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFormuleAchat()
    {
        return $this->formuleAchat;
    }

    /**
     * @param mixed $formuleAchat
     * @return Devises
     */
    public function setFormuleAchat($formuleAchat)
    {
        if (substr_count($formuleAchat, '%')==1)
        {
            $txAchat = intval($formuleAchat);
            $this->txAchat = $this->txReference * (100+$txAchat)/100;
        }
        elseif (substr_count($formuleAchat, '.')==1)
        {
            $this->txAchat = $this->txReference * (1+$formuleAchat);
        }
        else
            $this->txAchat = $this->txReference + $formuleAchat;

        $this->formuleAchat = $formuleAchat;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFormuleVente()
    {
        return $this->formuleVente;
    }

    /**
     * @param mixed $formuleVente
     * @return Devises
     */
    public function setFormuleVente($formuleVente)
    {
        if (substr_count($formuleVente, '%')==1)
        {
            $txVente = intval($formuleVente);
            $this->txVente = $this->txReference * (100+$txVente)/100;
        }
        elseif (substr_count($formuleVente, '.')==1)
        {
            $this->txVente = $this->txReference * (1+$formuleVente);
        }
        else
            $this->txVente = $this->txReference + $formuleVente;
        $this->formuleVente = $formuleVente;
        return $this;
    }

    
}