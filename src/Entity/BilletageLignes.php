<?php
/**
 * Created by PhpStorm.
 * User: Mouni
 * Date: 22/11/2016
 * Time: 10:39
 */

namespace App\Entity;


use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="BilletageLignes")
 */
class BilletageLignes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Billetages", inversedBy="billetageLignes")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="id")
     */
    private $idBilletage;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Billets", inversedBy="billetageLignes", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $billet;

    /**
     * @ORM\Column(type="float")
     */
    private $valeurBillet=0;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbBillet=0;

    /**
     * @ORM\Column(type="float")
     */
    private $valeurLigne=0;

    /**
     * billetageLignes constructor.
     * @param Billetages $billetages
     * @param ObjectManager $manager
     */
    public function __construct(Billetages $billetages, ObjectManager $manager)
    {
        $this->idBilletage=$billetages;
        $this->em=$manager;
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
    public function getIdBilletage()
    {
        return $this->idBilletage;
    }

    /**
     * @param $idBilletage
     * @return $this
     */
    public function setIdBilletage($idBilletage)
    {
        $this->idBilletage = $idBilletage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValeurBillet()
    {
        return $this->valeurBillet;
    }

    /**
     * @param $valeurBillet
     * @return $this
     */
    public function setValeurBillet($valeurBillet)
    {
        $this->valeurBillet = $valeurBillet;
        //($this->nbBillet !=0)?$this->valeurLigne=$valeurBillet*$this->nbBillet:$this->valeurLigne=0;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNbBillet()
    {
        return $this->nbBillet;
    }

    /**
     * @param $nbBillet
     * @return $this
     */
    public function setNbBillet($nbBillet)
    {
        $this->nbBillet = $nbBillet;
        //($this->valeurBillet !=0)?$this->valeurLigne=$nbBillet*$this->valeurBillet:$this->valeurLigne=0;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValeurLigne()
    {
        return $this->valeurLigne;
    }

    /**
     * @param mixed $valeurLigne
     */
    public function setValeurLigne($valeurLigne)
    {
        $this->valeurLigne = $valeurLigne;
    }

    /**
     * @return mixed
     */
    public function getBillet()
    {
        return $this->billet;
    }

    /**
     * @param mixed $billet
     * @return BilletageLignes
     */
    public function setBillet($billet)
    {
        $this->billet = $billet;
        return $this;
    }



}