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
 * @ORM\Table(name="DetteCreditDivers")
 */
class DetteCreditDivers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Caisses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idCaisse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idUtilisateurCreation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idUtilisateurRemb;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDC;

    /**
     * @ORM\Column(type="string")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string")
     */
    private $statut;

    /**
     * @ORM\Column(type="float")
     */
    private $mCredit;

    /**
     * @ORM\Column(type="float")
     */
    private $mDette;

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
    public function getIdCaisse()
    {
        return $this->idCaisse;
    }

    /**
     * @param mixed $idCaisse
     */
    public function setIdCaisse($idCaisse)
    {
        $this->idCaisse = $idCaisse;
    }

    /**
     * @return mixed
     */
    public function getIdUtilisateurCreation()
    {
        return $this->idUtilisateurCreation;
    }

    /**
     * @param mixed $idUtilisateurCreation
     */
    public function setIdUtilisateurCreation($idUtilisateurCreation)
    {
        $this->idUtilisateurCreation = $idUtilisateurCreation;
    }

    /**
     * @return mixed
     */
    public function getIdUtilisateurRemb()
    {
        return $this->idUtilisateurRemb;
    }

    /**
     * @param mixed $idUtilisateurRemb
     */
    public function setIdUtilisateurRemb($idUtilisateurRemb)
    {
        $this->idUtilisateurRemb = $idUtilisateurRemb;
    }

    /**
     * @return mixed
     */
    public function getDateDC()
    {
        return $this->dateDC;
    }

    /**
     * @param mixed $dateDC
     */
    public function setDateDC($dateDC)
    {
        $this->dateDC = $dateDC;
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
    }

    /**
     * @return mixed
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param mixed $statut
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

    /**
     * @return mixed
     */
    public function getMCredit()
    {
        return $this->mCredit;
    }

    /**
     * @param mixed $mCredit
     */
    public function setMCredit($mCredit)
    {
        $this->mCredit = $mCredit;
    }

    /**
     * @return mixed
     */
    public function getMDette()
    {
        return $this->mDette;
    }

    /**
     * @param mixed $mDette
     */
    public function setMDette($mDette)
    {
        $this->mDette = $mDette;
    }


}