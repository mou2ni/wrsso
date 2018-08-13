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
    const OUVERT='O', FERME='F';
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
     * @ORM\Column(type="string")
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes", inversedBy="operations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, referencedColumnName="id")
     */
    private $idCompteOperation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes", inversedBy="devises", cascade={"persist"})
     * @ORM\JoinColumn(name= "id_cpt_cv_devise",nullable=true)
     */
    private $CompteCvDevise;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\JourneeCaisses", mappedBy="idCaisse", cascade={"persist"})
     */
    private $journeeCaisses;

    /**
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return $this
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
     * @param $libelle
     * @return $this
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
     * @param $idCompteOperation
     * @return $this
     */
    public function setIdCompteOperation($idCompteOperation)
    {
        $this->idCompteOperation = $idCompteOperation;
        return $this;
    }

    public function __toString()
    {
        return ''.$this->getCode();
    }

    /**
     * @return mixed
     */
    public function getCompteCvDevise()
    {
        return $this->CompteCvDevise;
    }

    /**
     * @param mixed $CompteCvDevise
     * @return Caisses
     */
    public function setCompteCvDevise($CompteCvDevise)
    {
        $this->CompteCvDevise = $CompteCvDevise;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJourneeCaisses()
    {
        return $this->journeeCaisses;
    }

    /**
     * @param mixed $journeeCaisses
     * @return Caisses
     */
    public function setJourneeCaisses($journeeCaisses)
    {
        $this->journeeCaisses = $journeeCaisses;
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
     * @return Caisses
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return Caisses
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param mixed $status
     * @return Caisses
     */
    public function fermer()
    {
        $this->status = $this::FERME;
        return $this;
    }
    /**
     * @param mixed $status
     * @return Caisses
     */
    public function ouvrir()
    {
        $this->status = $this::OUVERT;
        return $this;
    }


}