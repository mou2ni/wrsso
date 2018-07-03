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
    public function getDateInventaire()
    {
        return $this->dateInventaire;
    }

    /**
     * @param mixed $dateInventaire
     */
    public function setDateInventaire($dateInventaire)
    {
        $this->dateInventaire = $dateInventaire;
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
     */
    public function setSoldeTotal($soldeTotal)
    {
        $this->soldeTotal = $soldeTotal;
    }


}