<?php
/**
 * Created by PhpStorm.
 * User: Hamado OUEDRAOGO
 * Date: 17/07/2019
 * Time: 17:44
 */

namespace App\Entity;


//use Doctrine\ORM\Mapping as ORM;
//use Symfony\Component\Validator\Constraints as Assert;

class BilletageLignes
{
    private $valeurBillet=0;

    private $nbBillet=0;

    /**
     * @return int
     */
    public function getTotalLigne(){
        return $this->getNbBillet()*$this->getValeurBillet();
    }

    /**
     * @return int
     */
    public function getValeurBillet()
    {
        return $this->valeurBillet;
    }

    /**
     * @param int $valeurBillet
     * @return BilletageLignes
     */
    public function setValeurBillet($valeurBillet)
    {
        $this->valeurBillet = $valeurBillet;
        return $this;
    }

    /**
     * @return int
     */
    public function getNbBillet()
    {
        return $this->nbBillet;
    }

    /**
     * @param int $nbBillet
     * @return BilletageLignes
     */
    public function setNbBillet($nbBillet)
    {
        $this->nbBillet = $nbBillet;
        return $this;
    }





}