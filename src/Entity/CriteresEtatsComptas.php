<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 26/02/2019
 * Time: 17:39
 */

namespace App\Entity;


class CriteresEtatsComptas
{

    private $compteDebut;

    private $compteFin;

    private $dateDebut;

    private $dateFin;

    public function __construct()
    {
        $auj=new \DateTime();
        $y=$auj->format('Y');
        $this->dateDebut=new \DateTime($y.'-01-01');
        $this->dateFin=new \DateTime($y.'-12-31');
    }

    /**
     * @return Comptes
     */
    public function getCompteDebut()
    {
        return $this->compteDebut;
    }

    /**
     * @param mixed $compteDebut
     * @return GrandLivres
     */
    public function setCompteDebut($compteDebut)
    {
        $this->compteDebut = $compteDebut;
        return $this;
    }

    /**
     * @return Comptes
     */
    public function getCompteFin()
    {
        return $this->compteFin;
    }

    /**
     * @param mixed $compteFin
     * @return GrandLivres
     */
    public function setCompteFin($compteFin)
    {
        $this->compteFin = $compteFin;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * @param \DateTime $dateDebut
     * @return GrandLivres
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * @param \DateTime $dateFin
     * @return GrandLivres
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
        return $this;
    }

}
