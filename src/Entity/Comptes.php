<?php
/**
 * Created by PhpStorm.
 * User: Mouni
 * Date: 22/11/2016
 * Time: 10:39
 */

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Comptes")
 */
class Comptes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Clients" , inversedBy="comptes", cascade={"persist"})
     * @ORM\JoinColumn(name="client", referencedColumnName="id", nullable=false)
     */
    private $client;

    /**
     * @ORM\Column(type="string")
     */
    private $numCompte;

    /**
     * @ORM\Column(type="string")
     */
    private $intitule;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default":0})
     */
    private $soldeCourant;

    /**
     * @ORM\Column(type="string")
     */
    private $typeCompte;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TransactionComptes", mappedBy="compte", cascade={"persist"})
     */
    private $transactionComptes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Utilisateurs", mappedBy="compteEcartCaisse", cascade={"persist"})
     */
    private $utilisateurCompteEcarts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Caisses", mappedBy="CompteCvDevise", cascade={"persist"})
     */
    private $cvdDevise;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Caisses", mappedBy="idCompteOperation", cascade={"persist"})
     */
    private $operation;

    /**
     * Comptes constructor.
     */
    public function __construct()
    {
        $this->transactionComptes = new ArrayCollection();
        $this->utilisateurCompteEcarts = new ArrayCollection();
        $this->cvdDevise = new ArrayCollection();
        $this->operation = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getNumCompte();
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     * @return Comptes
     */
    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumCompte()
    {
        return $this->numCompte;
    }

    /**
     * @param mixed $numCompte
     * @return Comptes
     */
    public function setNumCompte($numCompte)
    {
        $this->numCompte = $numCompte;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIntitule()
    {
        return $this->intitule;
    }

    /**
     * @param mixed $intitule
     * @return Comptes
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSoldeCourant()
    {
        return $this->soldeCourant;
    }

    /**
     * @param mixed $soldeCourant
     * @return Comptes
     */
    public function setSoldeCourant($soldeCourant)
    {
        $this->soldeCourant = $soldeCourant;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTypeCompte()
    {
        return $this->typeCompte;
    }

    /**
     * @param mixed $typeCompte
     * @return Comptes
     */
    public function setTypeCompte($typeCompte)
    {
        $this->typeCompte = $typeCompte;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransactionComptes()
    {
        return $this->transactionComptes;
    }

    /**
     * @param mixed $transactionComptes
     * @return Comptes
     */
    public function setTransactionComptes($transactionComptes)
    {
        $this->transactionComptes = $transactionComptes;
        return $this;
    }


    public function addTransactionComptes(TransactionComptes $transactionCompte)
    {
        $this->transactionComptes->add($transactionCompte);
        $transactionCompte->setTransaction($this);
    }

    public function removeTransactionComptes(TransactionComptes $transactionCompte)
    {
        $this->transactionComptes->removeElement($transactionCompte);
    }

    /**
     * @return mixed
     */
    public function getUtilisateurCompteEcarts()
    {
        return $this->utilisateurCompteEcarts;
    }

    public function addUtilisateurCompteEcarts(Utilisateurs $utilisateurs)
    {
        $this->utilisateurCompteEcarts->add($utilisateurs);
        $utilisateurs->setCompteEcartCaisse($this);
    }

    public function removeUtilisateurCompteEcarts(Utilisateurs $utilisateurs)
    {
        $this->utilisateurCompteEcarts->removeElement($utilisateurs);
    }

    /**
     * @param mixed $utilisateurCompteEcarts
     * @return Comptes
     */
    public function setUtilisateurCompteEcarts($utilisateurCompteEcarts)
    {
        $this->utilisateurCompteEcarts = $utilisateurCompteEcarts;
        return $this;
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
     * @return Comptes
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCvdDevise()
    {
        return $this->cvdDevise;
    }

    /**
     * @param mixed $cvdDevise
     * @return Comptes
     */
    public function setCvdDevise($cvdDevise)
    {
        $this->cvdDevise = $cvdDevise;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * @param mixed $operation
     * @return Comptes
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;
        return $this;
    }


}