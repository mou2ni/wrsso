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
 * @ORM\Table(name="billetages")
 */
class Billetages
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="float")
     */
    private $valeurTotal;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateBillettage;

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
    public function getValeurTotal()
    {
        return $this->valeurTotal;
    }

    /**
     * @param mixed $valeurTotal
     */
    public function setValeurTotal($valeurTotal)
    {
        $this->valeurTotal = $valeurTotal;
    }

    /**
     * @return mixed
     */
    public function getDateBillettage()
    {
        return $this->dateBillettage;
    }

    /**
     * @param mixed $dateBillettage
     */
    public function setDateBillettage($dateBillettage)
    {
        $this->dateBillettage = $dateBillettage;
    }

    public function __toString()
    {
        return ''.$this->getValeurTotal();
    }


}