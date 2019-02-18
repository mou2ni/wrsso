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

//use App\Entity\DeviseAchatVentes;
//use App\Entity\DeviseJournees;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeviseMouvementsRepository")
 * @ORM\Table(name="DeviseMouvements")
 * @ORM\HasLifecycleCallbacks()
 */
class DeviseMouvements
{
    const ACHAT='A', VENTE='V', INTERCAISSE='I';
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
     * @ORM\ManyToOne(targetEntity="App\Entity\DeviseIntercaisses" , inversedBy="deviseMouvements", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $deviseIntercaisse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devises")
     * @ORM\JoinColumn(nullable=false)
    */
    private $devise;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses" , inversedBy="deviseMouvements", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $journeeCaisse;



    /**
     * @ORM\Column(type="integer")
     */
    private $nombre=0;

    /**
     * @ORM\Column(type="float")
     */
    private $taux=0;

    private $sens='A';

    private function getSignedNombre($nombre){

        //vente : Signe toujours negatif
        if ($this->getSens()== $this::VENTE) {
            if ($nombre>0) $nombre = -$nombre;
        }

        //Achat : Signe toujours positif
        if ($this->getSens()== $this::ACHAT) {
            if ($nombre<0) $nombre = abs($nombre);
        }

        return $nombre;
    }

    /**
     * @return mixed
     */
    public function getContreValeur()
    {
        //mouvement CFA de la caisse : inverser le signe de nombre
        return -$this->nombre*$this->taux;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateMCvd(){

        /*$mCvd=$this->getNombre()*$this->getTaux();
        if ($this->getSens()=='v' or $this->getSens()=='V'){
            $this->setMCvdVente($mCvd);
            $this->setMCvdAchat(0);
        }else{
            $this->setMCvdAchat($mCvd);
            $this->setMCvdVente(0);
        }*/


        $this->journeeCaisse->maintenirMCvd();

    }

    /**
     * @ORM\PrePersist
     */
    public function increaseMCvd(){

        $this->journeeCaisse->updateM('mCvd', $this->getContreValeur());
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
        return $this->nombre; //nombre non signé
    }

    /**
     * @param $nombre
     * @return $this
     */
    public function setNombre($nombre)
    {
        $this->nombre=$this->getSignedNombre($nombre);
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
    public function getDeviseJournee()
    {
        return $this->deviseJournee;
    }


    /**
     * @param JourneeCaisses $journeeCaisse
     * @param ObjectManager $em
     * @return $this
     */
    public function setDeviseJourneeByJourneeCaisse(JourneeCaisses $journeeCaisse, ObjectManager $em)
    {
        // trouver la DeviseJournee é partir de la Devise saisie et la journeeCaisse passée par le constructeur
        $deviseJournee=$em->getRepository(DeviseJournees::class)
            ->findOneBy(['devise'=>$this->getDevise(), 'journeeCaisse'=>$journeeCaisse]);

        //die($deviseJournee);

        //Créer un nouveau au cas où çc n'existe pas
        if ($deviseJournee==null) {
            $deviseJournee=new DeviseJournees($journeeCaisse,$this->getDevise());
            //$deviseJournee->setDevise($this->getDevise())->setJourneeCaisse($journeeCaisse);
        }

        $this->setJourneeCaisse($journeeCaisse);
        $this->setDeviseJournee($deviseJournee);

        return $this;
    }

    /**
     * @param mixed $deviseJournee
     * @return DeviseMouvements
     */
    public function setDeviseJournee($deviseJournee)
    {
        $this->deviseJournee = $deviseJournee;
        $this->deviseJournee->updateMCvdAchatVente($this->getContreValeur());
        ($this->getSens()== $this::INTERCAISSE)
            ?$this->deviseJournee->updateQteIntercaisse($this->getNombre())
            :$this->deviseJournee->updateQteAchatVente($this->getNombre());
        //$this->deviseJournee->increaseMCvdAchat($this->mCvdAchat);
        //$this->deviseJournee->increaseMCvdVente($this->mCvdVente);
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
        $this->nombre=$this->getSignedNombre($this->nombre);
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
    public function getDeviseIntercaisse()
    {
        return $this->deviseIntercaisse;
    }

    /**
     * @param mixed $deviseIntercaisse
     * @return DeviseMouvements
     */
    public function setDeviseIntercaisse($deviseIntercaisse)
    {
        $this->deviseIntercaisse = $deviseIntercaisse;
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
     * @return DeviseMouvements
     */
    public function setJourneeCaisse($journeeCaisse)
    {
        $this->journeeCaisse = $journeeCaisse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return ($this->deviseIntercaisse)?$this::INTERCAISSE:($this->getContreValeur()>0)?$this::ACHAT:$this::VENTE;
    }

}