<?php
/**
 * Created by Hamado.
 * User: houedraogo
 * Date: 22/11/2016
 * Time: 10:39
 */

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity (repositoryClass="App\Repository\DeviseIntercaissesRepository")
 * @ORM\Table(name="DeviseIntercaisses")
 */
class DeviseIntercaisses
{
    const INIT='I', ANNULE='X', VALIDE='V', VALIDATION_AUTO='VA';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses", inversedBy="deviseIntercaisseSortants", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $journeeCaisseSource;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses", inversedBy="deviseIntercaisseEntrants", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $journeeCaisseDestination;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DeviseMouvements", mappedBy="deviseIntercaisse", cascade={"persist"})
     */
    private $deviseMouvements;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DeviseTmpMouvements", mappedBy="deviseIntercaisse", cascade={"persist"})
     */
    private $deviseTmpMouvements;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateIntercaisse;

    /*
     * @ORM\Column(type="float")

    private $qteIntercaisse;
*/
    /**
     * @ORM\Column(type="string")
     */
    private $statut='I';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observations;

    private $em;

    /**
     * DeviseRecus constructor.
     * @param JourneeCaisses $journeeCaisse
     * @param ObjectManager $manager
     */
    public function __construct(JourneeCaisses $journeeCaisse, ObjectManager $manager)
    {
        $this->journeeCaisseDestination=$journeeCaisse;
        $this->em=$manager;
        $this->deviseMouvements = new ArrayCollection();
        $this->deviseTmpMouvements = new ArrayCollection();
        $this->dateIntercaisse=new \DateTime();
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
    public function getJourneeCaisseSource()
    {
        return $this->journeeCaisseSource;
    }

    /**
     * @param mixed $journeeCaisseSource
     */
    public function setJourneeCaisseSource($journeeCaisseSource)
    {
        $this->journeeCaisseSource = $journeeCaisseSource;
    }

    /**
     * @return mixed
     */
    public function getJourneeCaisseDestination()
    {
        return $this->journeeCaisseDestination;
    }

    /**
     * @param $journeeCaisseDestination
     * @return $this
     */
    public function setJourneeCaisseDestination($journeeCaisseDestination)
    {
        $this->journeeCaisseDestination = $journeeCaisseDestination;
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
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

    /**
     * @return mixed
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * @param mixed $observations
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;
    }

    /**
     * @param mixed $observations
     */
    public function expendObservations($observations)
    {
        $this->observations = $this->observations.' | '.$observations;
    }

    /**
     * @return mixed
     */
    public function getDeviseMouvements()
    {
        return $this->deviseMouvements;
    }

    /**
     * @param mixed $deviseMouvements
     * @return DeviseIntercaisses
     */
    public function setDeviseMouvements($deviseMouvements)
    {
        $this->deviseMouvements = $deviseMouvements;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateIntercaisse()
    {
        return $this->dateIntercaisse;
    }

    /**
     * @param mixed $dateIntercaisse
     * @return DeviseIntercaisses
     */
    public function setDateIntercaisse($dateIntercaisse)
    {
        $this->dateIntercaisse = $dateIntercaisse;
        return $this;
    }

    /**
     * @param DeviseMouvements $deviseMouvement
     * @return $this
     */
    public function addDeviseMouvement(DeviseMouvements $deviseMouvement)
    {

        $deviseMouvement->setSens($deviseMouvement::INTERCAISSE)
            ->setDeviseJourneeByJourneeCaisse($this->journeeCaisseDestination, $this->em)
            ->setDeviseIntercaisse($this)        ;
        $this->deviseMouvements->add($deviseMouvement);
        $this->expendObservations($deviseMouvement->getDevise().' = '.$deviseMouvement->getNombre());

        //ajout du mouvement partenaire correspondant avec signe contraire
        $deviseMouvementPartenaire=new DeviseMouvements();
        $deviseMouvementPartenaire->setSens($deviseMouvementPartenaire::INTERCAISSE)
            ->setDevise($deviseMouvement->getDevise())
            ->setNombre(-$deviseMouvement->getNombre())
            ->setDeviseJourneeByJourneeCaisse($this->journeeCaisseSource, $this->em)
            ->setDeviseIntercaisse($this);
        $this->deviseMouvements->add($deviseMouvementPartenaire);


        return $this;
    }

    /**
     * @param DeviseMouvements $deviseMouvement
     * @return $this
     */
    public function removeDeviseMouvement(DeviseMouvements $deviseMouvement)
    {
        $this->deviseMouvements->removeElement($deviseMouvement);
        return $this;
    }

    /**
     * @param DeviseTmpMouvements $deviseTmpMouvement
     * @return $this
     */
    public function addDeviseTmpMouvement(DeviseTmpMouvements $deviseTmpMouvement)
    {

        /*$deviseMouvement->setSens($deviseMouvement::INTERCAISSE)
            ->setDeviseJourneeByJourneeCaisse($this->journeeCaisseDestination, $this->em)*/
        //$deviseTmpMouvement->setJourneeCaisse($this->journeeCaisseDestination);
        $deviseTmpMouvement->setDeviseIntercaisse($this)        ;
        $this->deviseTmpMouvements->add($deviseTmpMouvement);
        //$this->expendObservations($deviseMouvement->getDevise().' = '.$deviseMouvement->getNombre());

        //ajout du mouvement partenaire correspondant avec signe contraire
        //$deviseTmpMouvementPartenaire=new DeviseTmpMouvements();
        /*$deviseMouvementPartenaire->setSens($deviseMouvementPartenaire::INTERCAISSE)

            ->setDeviseJourneeByJourneeCaisse($this->journeeCaisseSource, $this->em)*/
        //$deviseTmpMouvementPartenaire->setJourneeCaisse($this->journeeCaisseSource);
        //$deviseTmpMouvementPartenaire->setDevise($deviseTmpMouvement->getDevise())
        //    ->setNombre(-$deviseTmpMouvement->getNombre())
         //   ->setDeviseIntercaisse($this);
        //$this->deviseTmpMouvements->add($deviseTmpMouvementPartenaire);


        return $this;
    }

    /**
     * @param DeviseTmpMouvements $deviseTmpMouvement
     * @return $this
     */
    public function removeDeviseTmpMouvement(DeviseTmpMouvements $deviseTmpMouvement)
    {
        $this->deviseTmpMouvements->removeElement($deviseTmpMouvement);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeviseTmpMouvements()
    {
        return $this->deviseTmpMouvements;
    }

    /**
     * @param mixed $deviseTmpMouvements
     * @return DeviseIntercaisses
     */
    public function setDeviseTmpMouvements($deviseTmpMouvements)
    {
        $this->deviseTmpMouvements = $deviseTmpMouvements;
        return $this;
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
     * @return DeviseIntercaisses
     */
    public function setEm($em)
    {
        $this->em = $em;
        return $this;
    }




}