<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 26/02/2019
 * Time: 17:39
 */

namespace App\Entity;


use DateTime;

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
        $m=$auj->format('m');
        $this->dateDebut=new \DateTime($y.'-'.$m.'-01 00:00:00');
        $this->dateFin=new \DateTime($y.'-'.$m.'-31 23:59:59');
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

        return new \DateTime($this->dateDebut->format('Y-m-d').' 00:00:00');
    }

    /**
     * @param $dateDebut
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
        return new \DateTime($this->dateFin->format('Y-m-d').' 23:59:59');
    }

    /**
     * @param $dateFin
     * @return GrandLivres
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
        return $this;
    }

}
