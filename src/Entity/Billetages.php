<?php
/**
 * Created by PhpStorm.
 * User: Mouni
 * Date: 22/11/2016
 * Time: 10:39
 */

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\OneToMany(targetEntity="App\Entity\BilletageLignes", mappedBy="idBilletage", cascade={"persist"})
     */
    private $billetageLignes;

    public function __construct()
    {
        $this->billetageLignes = new ArrayCollection();
    }

    /////// AJOUT HAMADO

    /*public function addBilletageLignes(BilletageLignes $billetageLigne)
    {
        $this->billetageLignes->add($billetageLigne);
        $billetageLigne->setIdBilletage($this);
        //$this->valeurTotal+=$billetageLigne->getNbBillet()*$billetageLigne->getValeurBillet();
    }
    */
    public function addBilletageLignes(BilletageLignes $billetageLignes)
    {
        $this->billetageLignes->add($billetageLignes);
        $billetageLignes->setIdBilletage($this);
    }

    public function removeBilletageLignes(BilletageLignes $billetageLigne)
    {
        $this->billetageLignes->removeElement($billetageLigne);
    }

    //// FIN AJOUT HAMADO

    /*public function addTransfertInternationaux(TransfertInternationaux $transfertInternationaux)
    {
        $this->transfertInternationaux->add($transfertInternationaux);
        $transfertInternationaux->setIdJourneeCaisse($this);
    }

    public function removeTransfertInternationaux(TransfertInternationaux $transfertInternationaux)
    {
        $this->transfertInternationaux->removeElement($transfertInternationaux);
    }*/

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
     * @param $valeurTotal
     * @return $this
     */
    public function setValeurTotal($valeurTotal)
    {
        $this->valeurTotal = $valeurTotal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateBillettage()
    {
        return $this->dateBillettage;
    }

    /**
     * @param $dateBillettage
     * @return $this
     */
    public function setDateBillettage($dateBillettage)
    {
        $this->dateBillettage = $dateBillettage;
        return $this;
    }

    public function __toString()
    {
        return ''.$this->getValeurTotal();
    }

    /**
     * @return Collection|BilletageLignes[]
     */
    public function getBilletageLignes()
    {
        return $this->billetageLignes;
    }

    /**
     * @param mixed $billetageLignes
     * @return Billetages
     */
    public function setBilletageLignes($billetageLignes)
    {
        $this->billetageLignes = $billetageLignes;
        return $this;
    }


}