<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 20/07/2018
 * Time: 17:37
 */

namespace App\Entity;

use App\Entity\Devises;
use App\Entity\DeviseJournees;
use App\Entity\DeviseMouvements;
use App\Entity\DeviseRecus;

use Doctrine\Common\Collections\ArrayCollection;
use phpDocumentor\Reflection\Types\String_;


class DeviseAchatVentes
{

    /*private $devise;
    private $deviseJournee;
    private $deviseMouvement;
    private $deviseRecu;*/

    private $devise;
    private $sens;
    private $nombre;
    private $mCvd;
    private $taux;
    private $dateRecu;
    private $nomPrenom;
    private $typePiece;
    private $numPiece;
    private $expireLe;
    private $paysPiece;
    private $motif;
    private $journalAchatVente;

    public function __construct()
    {
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
     * @return AchatVenteDevises
     */
    public function setDevise($devise)
    {
        $this->devise = $devise;
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
     * @return AchatVenteDevises
     */
    public function setSens($sens)
    {
        $this->sens = $sens;
        return $this;
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
     * @return AchatVenteDevises
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMCvd()
    {
        return $this->nombre*$this->taux;
    }

    /**
     * @param mixed $mCvd
     * @return AchatVenteDevises
     */
    public function setMCvd()
    {
        $this->mCvd = $this->nombre*$this->taux;;
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
     * @return AchatVenteDevises
     */
    public function setTaux($taux)
    {
        $this->taux = $taux;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateRecu()
    {
        return $this->dateRecu;
    }

    /**
     * @param mixed $dateRecu
     * @return AchatVenteDevises
     */
    public function setDateRecu($dateRecu)
    {
        $this->dateRecu = $dateRecu;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNomPrenom()
    {
        return $this->nomPrenom;
    }

    /**
     * @param mixed $nomPrenom
     * @return AchatVenteDevises
     */
    public function setNomPrenom($nomPrenom)
    {
        $this->nomPrenom = $nomPrenom;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTypePiece()
    {
        return $this->typePiece;
    }

    /**
     * @param mixed $typePiece
     * @return AchatVenteDevises
     */
    public function setTypePiece($typePiece)
    {
        $this->typePiece = $typePiece;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpireLe()
    {
        return $this->expireLe;
    }

    /**
     * @param mixed $expireLe
     * @return AchatVenteDevises
     */
    public function setExpireLe($expireLe)
    {
        $this->expireLe = $expireLe;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMotif()
    {
        return $this->motif;
    }

    /**
     * @param mixed $motif
     * @return AchatVenteDevises
     */
    public function setMotif($motif)
    {
        $this->motif = $motif;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJournalAchatVente()
    {
        return $this->journalAchatVente;
    }

    /**
     * @param mixed $journalAchatVente
     * @return DeviseAchatVentes
     */
    public function setJournalAchatVente($journalAchatVente)
    {
        $this->journalAchatVente = $journalAchatVente;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumPiece()
    {
        return $this->numPiece;
    }

    /**
     * @param mixed $numPiece
     * @return DeviseAchatVentes
     */
    public function setNumPiece($numPiece)
    {
        $this->numPiece = $numPiece;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaysPiece()
    {
        return $this->paysPiece;
    }

    /**
     * @param mixed $paysPiece
     * @return DeviseAchatVentes
     */
    public function setPaysPiece($paysPiece)
    {
        $this->paysPiece = $paysPiece;
        return $this;
    }
    



}