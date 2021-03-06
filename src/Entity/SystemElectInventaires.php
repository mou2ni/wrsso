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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="SystemElectInventaires")
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\OneToMany(targetEntity="App\Entity\SystemElectLigneInventaires", mappedBy="idSystemElectInventaire", cascade={"persist"}, orphanRemoval=true)
     */
    private $systemElectLigneInventaires;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\JourneeCaisses", mappedBy="systemElectInventOuv")
     * @ORM\JoinColumn(nullable=true)
     */
    private $journeeCaisse;

    ////METTRE EN BD A RENSEIGNER POUR TOUT AJOUT DE LIGNE
    /**
     * @ORM\Column(type="float")
     * @Assert\GreaterThanOrEqual(value="0", message="la valeur doit positive")
     */
    private $soldeTotal;

    /**
     * @ORM\Column(type="string")
     */
    private $systemElectLigneInventaire='';

    public function __construct()
    {
        $this->dateInventaire=new \DateTime('now');
        $this->systemElectLigneInventaires = new ArrayCollection();
    }

    public function addSystemElectLigneInventaires(SystemElectLigneInventaires $systemElectLigneInventaire)
    {
        /*****TEST D'EXISTANCE D'UNE LIGNE DEJA LE MEME SYSTEMELECT QUE LA NOUVELLE LIGNE*****/
        $exist=false;
        foreach ($this->systemElectLigneInventaires as $sei){
            if ($systemElectLigneInventaire->getIdSystemElect()==$sei->getIdSystemElect() && $systemElectLigneInventaire->getId()!=$sei->getId())
                $exist=true;
        }
        if (!$exist) { /////AJOUT S'IL N'EXISTE PAS ENCORE DE LIGNE PORTANT LE MEME LIBELLE
            $this->systemElectLigneInventaires->add($systemElectLigneInventaire);
            $systemElectLigneInventaire->setIdSystemElectInventaire($this);
        }
        /*else {
            dump('error'); die();
        }*/

    }

    public function removeSystemElectLigneInventaires(SystemElectLigneInventaires $systemElectLigneInventaire)
    {
        $this->systemElectLigneInventaires->removeElement($systemElectLigneInventaire);
        die();
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
        $valeurTotal=0;
        foreach ($this->getSystemElectLigneInventaires() as $ligne){
            $valeurTotal += $ligne->getSolde();
        }
        return $valeurTotal;
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

    /**
     * @return mixed
     */
    public function getJourneeCaisse()
    {
        return $this->journeeCaisse;
    }

    /**
     * @param mixed $journeeCaisse
     * @return SystemElectInventaires
     */
    public function setJourneeCaisse($journeeCaisse)
    {
        $this->journeeCaisse = $journeeCaisse;
        return $this;
    }




    public function __toString()
    {
        return ''.$this->getSoldeTotal().' du '.(string)$this->getDateInventaire()->format('d-m-Y H:i:s');
    }

    /**
     * @return mixed
     */
    public function getValeurTotal()
    {
        $valeurTotal=0;
        foreach ($this->getSystemElectLigneInventaires() as $ligne){
            $valeurTotal += $ligne->getSolde();
        }
        return $valeurTotal;
    }

    /**
     * @return mixed
     */
    public function maintenir()
    {
        $this->soldeTotal=0;
        foreach ($this->getSystemElectLigneInventaires() as $ligne){
            $this->soldeTotal += $ligne->getSoldeTotal();
        }
        return $this;
    }


    /**
     * @ORM\PreUpdate
     */
    public function fillOnUpdate(){
        //$this->fillBilletageLignes();
        $valeurTotal = 0;
        foreach ($this->getSystemElectLigneInventaires() as $ligne){
            $valeurTotal += $ligne->getSolde();
        }
        $this->soldeTotal=$valeurTotal;

    }

    /**
     * @ORM\PrePersist
     */
    public function fillOnPersist(){
        //$this->fillBilletageLignes();
        $this->fillOnUpdate();
    }

    /**
     * @return mixed
     */
    public function getSystemElectLigneInventaire()
    {
        return $this->systemElectLigneInventaire;
    }

    /**
     * @param mixed $systemElectLigneInventaire
     * @return SystemElectInventaires
     */
    public function setSystemElectLigneInventaire($systemElectLigneInventaire)
    {
        $this->systemElectLigneInventaire = $systemElectLigneInventaire;
        return $this;
    }

}