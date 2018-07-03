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
 * @ORM\Table(name="Salaires")
 */
class Salaires
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idTrans;

    /**
     * @ORM\Column(type="float")
     */
    private $periodeSalaire;

    /**
     * @ORM\Column(type="float")
     */
    private $mSalaire;

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
    public function getIdTrans()
    {
        return $this->idTrans;
    }

    /**
     * @param mixed $idTrans
     */
    public function setIdTrans($idTrans)
    {
        $this->idTrans = $idTrans;
    }

    /**
     * @return mixed
     */
    public function getPeriodeSalaire()
    {
        return $this->periodeSalaire;
    }

    /**
     * @param mixed $periodeSalaire
     */
    public function setPeriodeSalaire($periodeSalaire)
    {
        $this->periodeSalaire = $periodeSalaire;
    }

    /**
     * @return mixed
     */
    public function getMSalaire()
    {
        return $this->mSalaire;
    }

    /**
     * @param mixed $mSalaire
     */
    public function setMSalaire($mSalaire)
    {
        $this->mSalaire = $mSalaire;
    }


}