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
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="billetages")
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\Column(type="datetime")
     */
    private $dateBillettage;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BilletageLignes", mappedBy="billetages", cascade={"persist"})
     */
    private $billetageLignes;

    private $billetageLigneAffiches;

    ////METTRE EN BD A RENSEIGNER POUR TOUT AJOUT DE LIGNE
    /**
     * @ORM\Column(type="float")
     * @Assert\GreaterThanOrEqual(value="0", message="la valeur doit positive")
     */
    private $valeurTotal=0;

    /*
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, unique=false)

    private $journeeCaisse;*/

    private $em;

    public function __construct()
    {
        $this->dateBillettage=new \DateTime('now');
        $this->billetageLignes = new ArrayCollection();
        $this->billetageLigneAffiches = new ArrayCollection();
    }

    public function fillBilletageLignes(){
        //$this->billetageLignes = new ArrayCollection();
        foreach ($this->billetageLignes as $bl){

            $this->billetageLignes->remove($bl);
        }

        foreach ($this->billetageLigneAffiches as $billetageLigne){
            //$this->billetageLignes->remove($billetageLigne);
            //$this->removeBilletageLignes($billetageLigne);
            if ($billetageLigne->getNbBillet()>0) {
                $this->billetageLignes->add($billetageLigne);
            }

        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function fillOnUpdate(){
        //$this->fillBilletageLignes();
        $total = 0;
        foreach ($this->billetageLignes as $bl)
        {
            $total += $bl->getValeurLigne();
        }
        $this->valeurTotal=$total;

    }

    /**
     * @ORM\PrePersist
     */
    public function fillOnPersist(){
        //$this->fillBilletageLignes();
        $this->fillOnUpdate();
    }


    public function addBilletageLigneAffiche(BilletageLignes $billetageLigne)
    {
        //dump($billetageLigne);die();
        $this->billetageLigneAffiches->add($billetageLigne);
        $billetageLigne->setBilletages($this);
        if ($billetageLigne->getNbBillet()>0) {
            $this->addBilletageLignes($billetageLigne);
        }

    }

    public function removeBilletageLigneAffiche(BilletageLignes $billetageLigne)
    {
        $this->billetageLigneAffiches->removeElement($billetageLigne);
    }

    public function addBilletageLigne(BilletageLignes $billetageLigne)
{
    $this->billetageLignes->add($billetageLigne);
    $billetageLigne->setBilletages($this);
    $this->valeurTotal += $billetageLigne->getValeurLigne();
}

    public function removeBilletageLignes(BilletageLignes $billetageLigne)
    {
        $this->billetageLignes->removeElement($billetageLigne);
        $this->valeurTotal -= $billetageLigne->getValeurLigne();
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
     * @return mixed
     */
    public function maintenir()
    {
        $this->valeurTotal=0;
        foreach ($this->getBilletageLignes() as $ligne){
            $this->valeurTotal += $ligne->getValeurLigne();
        }
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
    public function  setBilletageLignes($billetageLignes)
    {
        $this->billetageLignes = $billetageLignes;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getBilletageLigneAffiches(): ArrayCollection
    {
        if (!$this->billetageLigneAffiches)$this->billetageLigneAffiches=new ArrayCollection();
        $billets=$this->em->getRepository('App:Billets')->findActive();
        foreach ($billets as $billet){
            $billetageLigne = new BilletageLignes();
            $billetageLigne->setBillet($billet)->setValeurBillet($billet->getValeur())->setNbBillet(0);
            //dump($this->billetageLignes);die();
            foreach ($this->billetageLignes as $ligneEnreg){
                if ($ligneEnreg->getBillet()->getValeur()==$billet->getValeur()){
                    echo($ligneEnreg->getNbBillet());echo(":");//echo($billet->getValeur()); echo("===\n");
                    //dump($billet);die();
                    $billetageLigne->setNbBillet($ligneEnreg->getNbBillet());
                }
            }
            //die();

            //$this->addBilletageLigneAffiche($billetageLigne);
            $this->addBilletageLigneAffiche($billetageLigne);
        }


        return $this->billetageLigneAffiches;
    }

    /**
     * @param ArrayCollection $billetageLigneAffiches
     * @return Billetages
     */
    public function setBilletageLigneAffiches(ArrayCollection $billetageLigneAffiches): Billetages
    {
        $this->billetageLigneAffiches = $billetageLigneAffiches;
        return $this;
    }

    /**
     * @return ObjectManager
     */
    public function getEm(): ObjectManager
    {
        return $this->em;
    }

    /**
     * @param ObjectManager $em
     * @return Billetages
     */
    public function setEm(ObjectManager $em): Billetages
    {
        $this->em = $em;
        return $this;
    }



}