<?php
/**
 * Created by Hamado.
 * Date: 21/01/2019
 * Time: 07:14
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class LigneSalaires
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Salaires", inversedBy="ligneSalaires", cascade={"persist"})
     */
    private $salaire;

    /**
     * @ORM\Column(type="float")
     */
    private $mSalaireBase;

    /**
     * @ORM\Column(type="float")
     */
    private $mIndemLogement;

    /**
     * @ORM\Column(type="float")
     */
    private $mIndemTransport;

    /**
     * @ORM\Column(type="float")
     */
    private $mIndemFonction;

    /**
     * @ORM\Column(type="float")
     */
    private $mIndemAutres;

    /**
     * @ORM\Column(type="float")
     */
    private $mSocialeSalarie;

    /**
     * @ORM\Column(type="float")
     */
    private $mSocialePatronale;

    /**
     * @ORM\Column(type="float")
     */
    private $mImpotSalarie;

    /**
     * @ORM\Column(type="float")
     */
    private $mTaxePatronale;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes", cascade={"persist"})
     */
    private $compte;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Salaires
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSalaire()
    {
        return $this->salaire;
    }

    /**
     * @param mixed $salaire
     * @return Salaires
     */
    public function setSalaire($salaire)
    {
        $this->salaire = $salaire;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMSalaireNet()
    {
        return $this->mSalaireNet;
    }

    /**
     * @param mixed $mSalaireNet
     * @return Salaires
     */
    public function setMSalaireNet($mSalaireNet)
    {
        $this->mSalaireNet = $mSalaireNet;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompte()
    {
        return $this->compte;
    }

    /**
     * @param mixed $compte
     * @return Salaires
     */
    public function setCompte($compte)
    {
        $this->compte = $compte;
        return $this;
    }


}