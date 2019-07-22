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
 * @ORM\Entity (repositoryClass="App\Repository\BilletsRepository")
 * @ORM\Table(name="billets")
 */
class Billets
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @ORM\Column(unique=true)
     */
    private $valeur;

    /*
     * @ORM\OneToMany(targetEntity="App\Entity\BilletageLignes", mappedBy="billet", cascade={"persist"})
     */
    private $billetageLignes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devises", inversedBy="billets", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $devise;


    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive=true;


    public function __toString()
    {
        return ''.$this->getValeur();
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
    public function getisActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     * @return Billets
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBilletageLignes()
    {
        return $this->billetageLignes;
    }

    /**
     * @param mixed $billetageLignes
     * @return Billets
     */
    public function setBilletageLignes($billetageLignes)
    {
        $this->billetageLignes = $billetageLignes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * @param mixed $valeur
     * @return Billets
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDevise()
    {
        return $this->devise;
    }

    /**
     * @param mixed $devise
     * @return Billets
     */
    public function setDevise($devise)
    {
        $this->devise = $devise;
        return $this;
    }





}