<?php
/**
 * Created by PhpStorm.
 * User: Mouni
 * Date: 22/11/2016
 * Time: 10:39
 */

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity (repositoryClass="App\Repository\CaissesRepository")
 * @ORM\Table(name="Caisses")
 */
class Caisses
{
    const OUVERT='O', FERME='F';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string")
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes", inversedBy="caisses", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, referencedColumnName="id")
     */
    private $compteOperation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes", inversedBy="devises", cascade={"persist"})
     * @ORM\JoinColumn(name= "id_cpt_cv_devise",nullable=true)
     */
    private $CompteCvDevise;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\JourneeCaisses", mappedBy="caisse", cascade={"persist"})
     */
    private $journeeCaisses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Utilisateurs", mappedBy="lastCaisse", cascade={"persist"})
     */
    private $utilisateurs;

    /*
    **
      @ORM\OneToOne(targetEntity="App\Entity\JourneeCaisses")
     * @ORM\JoinColumn(nullable=true)
     */
    //private $journeeOuverte;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $journeeOuverteId;

    private $em;

    /**
     * @ORM\Column(type="string")
     */
    private $statut=self::FERME;

    /**
     * Caisses constructor.
     */
    public function __construct(ObjectManager $em)
    {
        $this->em=$em;
        $this->journeeCaisses=new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param $libelle
     * @return $this
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    public function __toString()
    {
        return ''.$this->getCode();
    }

    /**
     * @return mixed
     */
    public function getCompteCvDevise()
    {
        return $this->CompteCvDevise;
    }

    /**
     * @param mixed $CompteCvDevise
     * @return Caisses
     */
    public function setCompteCvDevise($CompteCvDevise)
    {
        $this->CompteCvDevise = $CompteCvDevise;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJourneeCaisses()
    {
        return $this->journeeCaisses;
    }

    /**
     * @param mixed $journeeCaisses
     * @return Caisses
     */
    public function setJourneeCaisses($journeeCaisses)
    {
        $this->journeeCaisses = $journeeCaisses;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     * @return Caisses
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return $this
     */
    public function fermer()
    {
        $this->status = $this::FERME;
        return $this;
    }

    /**
     * @return $this
     */
    public function ouvrir()
    {
        $this->status = $this::OUVERT;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompteOperation()
    {
        return $this->compteOperation;
    }

    /**
     * @param mixed $compteOperation
     * @return Caisses
     */
    public function setCompteOperation($compteOperation)
    {
        $this->compteOperation = $compteOperation;
        return $this;
    }

    public function addJourneeCaisse(JourneeCaisses $journeeCaisse)
    {
        $journeeCaisse->setCaisse($this);
        $this->journeeCaisses->add($journeeCaisse);
    }

    public function removeJourneeCaisse(JourneeCaisses $journeeCaisse)
    {
        $this->journeeCaisses->removeElement($journeeCaisse);
    }
    
    public function getNouvelleJournee(){

        //dump($this);die();
        $nouvellleJournee=$this->em->getRepository(JourneeCaisses::class)->findOneJourneeActive($this);
        if(!$nouvellleJournee){
            $nouvellleJournee=new JourneeCaisses();
            $this->addJourneeCaisse($nouvellleJournee);
        }
        
        return $nouvellleJournee;
    }

    /**
     * @return ObjectManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param ObjectManager $em
     * @return Caisses
     */
    public function setEm($em)
    {
        $this->em = $em;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJourneeOuverte()
    {
        return $this->journeeOuverte;
    }

    /**
     * @param mixed $journeeOuverte
     * @return Caisses

    public function setJourneeOuverte($journeeOuverte)
    {
        $this->journeeOuverte = $journeeOuverte;
        return $this;
    }*/

    /**
     * @return mixed
     */
    public function getUtilisateurs()
    {
        return $this->utilisateurs;
    }

    /**
     * @param mixed $utilisateurs
     * @return Caisses
     */
    public function setUtilisateurs($utilisateurs)
    {
        $this->utilisateurs = $utilisateurs;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param mixed $statut
     * @return Caisses
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJourneeOuverteId()
    {
        return $this->journeeOuverteId;
    }

    /**
     * @param mixed $journeeOuverteId
     * @return Caisses
     */
    public function setJourneeOuverteId($journeeOuverteId)
    {
        $this->journeeOuverteId = $journeeOuverteId;
        return $this;
    }



}