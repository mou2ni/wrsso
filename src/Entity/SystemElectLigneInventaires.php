<?php
/**
 * Created by PhpStorm.
 * User: Mouni
 * Date: 22/11/2016
 * Time: 10:39
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\SystemElectInventaires", inversedBy="systemElectLigneInventaires", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $idSystemElectInventaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SystemElects",inversedBy="systemElectInventaire")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idSystemElect;

    /**
     * @ORM\Column(type="float")
     */
    private $solde = 0;

    public function __toString()
    {
        return ''.$this->getSolde();
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
     * @return SystemElectLigneInventaires
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @return SystemElectLigneInventaires
     */
    public function setIdSystemElectInventaire($idSystemElectInventaire)
    {
        $this->idSystemElectInventaire = $idSystemElectInventaire;
        return $this;
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
     * @return SystemElectLigneInventaires
     */
    public function setIdSystemElect($idSystemElect)
    {
        $this->idSystemElect = $idSystemElect;
        return $this;
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
     * @return SystemElectLigneInventaires
     */
    public function setSolde($solde)
    {
        $this->solde = $solde;
        return $this;
    }


}