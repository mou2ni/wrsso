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
 * @ORM\Table(name="SystemElectLigneInventaires")
 */
class SystemElectLigneInventaires
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SystemElectInventaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idSystemElectInventaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SystemElects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idSystemElect;

    /**
     * @ORM\Column(type="float")
     */
    private $solde;

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
    public function getIdSystemElectInventaire()
    {
        return $this->idSystemElectInventaire;
    }

    /**
     * @param mixed $idSystemElectInventaire
     */
    public function setIdSystemElectInventaire($idSystemElectInventaire)
    {
        $this->idSystemElectInventaire = $idSystemElectInventaire;
    }

    /**
     * @return mixed
     */
    public function getIdSystemElect()
    {
        return $this->idSystemElect;
    }

    /**
     * @param mixed $idSystemElect
     */
    public function setIdSystemElect($idSystemElect)
    {
        $this->idSystemElect = $idSystemElect;
    }

    /**
     * @return mixed
     */
    public function getSolde()
    {
        return $this->solde;
    }

    /**
     * @param mixed $solde
     */
    public function setSolde($solde)
    {
        $this->solde = $solde;
    }


}