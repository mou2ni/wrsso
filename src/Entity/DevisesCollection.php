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


class DevisesCollection
{

    private $id;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Billets", mappedBy="devise", cascade={"persist"})
     */
    private $devises;

    /**
     * DevisesCollection constructor.
     * @param $devise
     */
    public function __construct()
    {
        $this->devises = new ArrayCollection();
    }

    public function addDevises(Devises $devise)
    {
        $this->devises->add($devise);
        //$devise->se($this);
    }

    public function removeDevise(Devises $devises)
    {
        $this->devises->removeElement($devises);
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
     * @return DevisesCollection
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDevises()
    {
        return $this->devises;
    }

    /**
     * @param mixed $devises
     * @return DevisesCollection
     */
    public function setDevises($devises)
    {
        $this->devises = $devises;
        return $this;
    }



}