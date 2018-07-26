<?php
/**
 * Created by PhpStorm.
 * User: Mouni
 * Date: 22/11/2016
 * Time: 10:39
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

//use App\Entity\DeviseAchatVentes;
//use App\Entity\DeviseJournees;

/**
 * @ORM\Entity
 * @ORM\Table(name="DeviseMouvements")
 * @ORM\HasLifecycleCallbacks()
 */
class DeviseMouvements
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DeviseJournees" , inversedBy="deviseMouvements", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $deviseJournee;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DeviseRecus" , inversedBy="deviseMouvements", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $deviseRecu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devises")
     * @ORM\JoinColumn(nullable=false)
    */
    private $devise;



    /**
     * @ORM\Column(type="integer")
     */
    private $nombre;

    /**
     * @ORM\Column(type="float")
     */
    private $taux;


    /**
     * @ORM\Column(type="float")
     */
    private $mCvdAchat;

    /**
     * @ORM\Column(type="float")
     */
    private $mCvdVente;

    private $sens;
    private $contreValeur;

    /**
     * DeviseMouvements constructor.
     * @param $deviseJournee
     * @param $deviseRecu

    public function __construct(DeviseJournees $deviseJournee, DeviseRecus $deviseRecu)
    {
        $this->deviseJournee = $deviseJournee;
        $this->deviseRecu = $deviseRecu;
    }*/


    /**
     * @ORM\PreUpdate
     */
    public function updateMCvd(){

        $mCvd=$this->getNombre()*$this->getTaux();
        if ($this->getSens()=='v' or $this->getSens()=='V'){
            $this->setMCvdVente($mCvd);
            $this->setMCvdAchat(0);
        }else{
            $this->setMCvdAchat($mCvd);
            $this->setMCvdVente(0);
        }
    }

    /**
     * @ORM\PrePersist
     */
    public function createMCvd(){
        $this->updateMCvd();
    }



    public function setFromDeviseAchatVente(DeviseAchatVentes $deviseAchatVente, DeviseJournees $deviseJournee)
    {
        $this->setDeviseJournee($deviseJournee);
        $this->setNombre($deviseAchatVente->getNombre());
        $this->setTaux($deviseAchatVente->getTaux());

        return $this;

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
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
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
     * @return DeviseMouvements
     */
    public function setTaux($taux)
    {
        $this->taux = $taux;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMCvdAchat()
    {
        return $this->mCvdAchat;
    }

    /**
     * @param mixed $mCvdAchat
     * @return DeviseMouvements
     */
    public function setMCvdAchat($mCvdAchat)
    {
        $this->mCvdAchat = $mCvdAchat;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMCvdVente()
    {
        return $this->mCvdVente;
    }

    /**
     * @param mixed $mCvdVente
     * @return DeviseMouvements
     */
    public function setMCvdVente($mCvdVente)
    {
        $this->mCvdVente = $mCvdVente;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeviseJournee()
    {
        return $this->deviseJournee;
    }

    /**
     * @param mixed $deviseJournee
     * @return DeviseMouvements
     */
    public function setDeviseJournee($deviseJournee)
    {
        $this->deviseJournee = $deviseJournee;
        $this->deviseJournee->increaseMCvdAchat($this->mCvdAchat);
        $this->deviseJournee->increaseMCvdVente($this->mCvdVente);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeviseRecu()
    {
        return $this->deviseRecu;
    }

    /**
     * @param mixed $deviseRecu
     * @return DeviseMouvements
     */
    public function setDeviseRecu($deviseRecu)
    {
        $this->deviseRecu = $deviseRecu;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSens()
    {
        return $this->sens;
    }

    /**
     * @param mixed $sens
     * @return DeviseMouvements
     */
    public function setSens($sens)
    {
        $this->sens = $sens;
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
     * @return DeviseMouvements
     */
    public function setDevise($devise)
    {
        $this->devise = $devise;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContreValeur()
    {
        return $this->nombre*$this->taux;
    }

    /**
     * @param mixed $contreValeur
     * @return DeviseMouvements
     */
    public function setContreValeur($contreValeur)
    {
        $this->contreValeur = $contreValeur;
        return $this;
    }


   }