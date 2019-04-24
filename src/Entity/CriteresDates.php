<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 26/02/2019
 * Time: 17:39
 */

namespace App\Entity;


class CriteresDates
{
    private $dateDebut;

    private $dateFin;

    public function __construct()
    {
        /*$auj=new \DateTime();
        $y=$auj->format('Y');
        $this->dateDebut=new \DateTime($y.'-01-01');
        $this->dateFin=new \DateTime($y.'-12-31');*/

        $auj=new \DateTime();
        $string_auj=$auj->format('Y-m-d');
        //$hier=$auj->sub(new \DateInterval('P1D'));
        //$string_hier=$hier->format('Y-m-d');
        $this->dateDebut=new \DateTime($string_auj.' 00:00:00');
        $this->dateFin=new \DateTime($string_auj.' 23:59:59');
    }

    /**
     * @return \DateTime
     */
    public function getDateDebut()
    {
        $string_date=$this->dateDebut->format('Y-m-d');
        return new \DateTime($string_date.' 00:00:00');
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
        $string_date=$this->dateFin->format('Y-m-d');
        return new \DateTime($string_date.' 23:59:59');
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
