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
 * @ORM\Table(name="DeviseIntercaisses")
 */
class DeviseIntercaisses
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idJourneeCaisseSource;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JourneeCaisses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idJourneeCaissePartenaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devises")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idDevise;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DeviseMouvements", mappedBy="deviseIntercaisse", cascade={"persist"})
     */
    private $deviseMouvements;

    /**
     * @ORM\Column(type="float")
     */
    private $qteIntercaisse;

    /**
     * @ORM\Column(type="string")
     */
    private $statut;

    /**
     * @ORM\Column(type="string")
     */
    private $observations;

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
    public function getIdJourneeCaisseSource()
    {
        return $this->idJourneeCaisseSource;
    }

    /**
     * @param mixed $idJourneeCaisseSource
     */
    public function setIdJourneeCaisseSource($idJourneeCaisseSource)
    {
        $this->idJourneeCaisseSource = $idJourneeCaisseSource;
    }

    /**
     * @return mixed
     */
    public function getIdJourneeCaissePartenaire()
    {
        return $this->idJourneeCaissePartenaire;
    }

    /**
     * @param mixed $idJourneeCaissePartenaire
     */
    public function setIdJourneeCaissePartenaire($idJourneeCaissePartenaire)
    {
        $this->idJourneeCaissePartenaire = $idJourneeCaissePartenaire;
    }

    /**
     * @return mixed
     */
    public function getIdDevise()
    {
        return $this->idDevise;
    }

    /**
     * @param mixed $idDevise
     */
    public function setIdDevise($idDevise)
    {
        $this->idDevise = $idDevise;
    }

    /**
     * @return mixed
     */
    public function getMIntercaisse()
    {
        return $this->mIntercaisse;
    }

    /**
     * @param mixed $mIntercaisse
     */
    public function setMIntercaisse($mIntercaisse)
    {
        $this->mIntercaisse = $mIntercaisse;
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


}