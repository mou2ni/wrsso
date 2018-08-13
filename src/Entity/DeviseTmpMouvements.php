<?php
/**
 * Created by Hamado.
 * User: houedraogo
 * Date: 07/08/2018
 * Time: 14:10
 */

namespace App\Entity;

//use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeviseTmpMouvementsRepository")
 * @ORM\HasLifecycleCallbacks()
 */

class DeviseTmpMouvements
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DeviseIntercaisses" , inversedBy="deviseTmpMouvements", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $deviseIntercaisse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devises")
     * @ORM\JoinColumn(nullable=false)
     */
    private $devise;

    /*
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses")
     * @ORM\JoinColumn(nullable=true)

    private $journeeCaisse;*/

    /**
     * @ORM\Column(type="integer")
     */
    private $nombre=0;

    /**
     * @ORM\Column(type="float")
     */
    private $taux=0;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return DeviseTmpMouvements
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeviseIntercaisse()
    {
        return $this->deviseIntercaisse;
    }

    /**
     * @param mixed $deviseIntercaisse
     * @return DeviseTmpMouvements
     */
    public function setDeviseIntercaisse($deviseIntercaisse)
    {
        $this->deviseIntercaisse = $deviseIntercaisse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDevise()
    {
        return $this->devise;
    }

    /**
     * @param mixed $devise
     * @return DeviseTmpMouvements
     */
    public function setDevise($devise)
    {
        $this->devise = $devise;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     * @return DeviseTmpMouvements
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaux()
    {
        return $this->taux;
    }

    /**
     * @param mixed $taux
     * @return DeviseTmpMouvements
     */
    public function setTaux($taux)
    {
        $this->taux = $taux;
        return $this;
    }

}