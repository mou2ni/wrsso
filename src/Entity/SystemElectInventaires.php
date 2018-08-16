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
 * @ORM\Table(name="SystemElectInventaires")
 */
class SystemElectInventaires
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateInventaire;

    /**
     * @ORM\Column(type="float")
     */
    private $soldeTotal;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SystemElectLigneInventaires", mappedBy="idSystemElectInventaire", cascade={"persist"})
     */
    private $systemElectLigneInventaires;

    public function __construct()
    {
        $this->systemElectLigneInventaires = new ArrayCollection();
    }

    public function addSystemElectLigneInventaires(SystemElectLigneInventaires $systemElectLigneInventaire)
    {
        $this->systemElectLigneInventaires->add($systemElectLigneInventaire);
        $systemElectLigneInventaire->setIdSystemElectInventaire($this);
    }

    public function removeSystemElectLigneInventaires(SystemElectLigneInventaires $systemElectLigneInventaire)
    {
        $this->systemElectLigneInventaires->removeElement($systemElectLigneInventaire);
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
     * @return SystemElectInventaires
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateInventaire()
    {
        return $this->dateInventaire;
    }

    /**
     * @param mixed $dateInventaire
     * @return SystemElectInventaires
     */
    public function setDateInventaire($dateInventaire)
    {
        $this->dateInventaire = $dateInventaire;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSoldeTotal()
    {
        return $this->soldeTotal;
    }

    /**
     * @param mixed $soldeTotal
     * @return SystemElectInventaires
     */
    public function setSoldeTotal($soldeTotal)
    {
        $this->soldeTotal = $soldeTotal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSystemElectLigneInventaires()
    {
        return $this->systemElectLigneInventaires;
    }

    /**
     * @param mixed $systemElectLigneInventaires
     * @return SystemElectInventaires
     */
    public function setSystemElectLigneInventaires($systemElectLigneInventaires)
    {
        $this->systemElectLigneInventaires = $systemElectLigneInventaires;
        return $this;
    }



    public function __toString()
    {
        return ''.$this->getSoldeTotal().' du '.(string)$this->getDateInventaire()->format('d-m-Y H:i:s');
    }
}