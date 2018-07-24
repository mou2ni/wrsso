<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 20/07/2018
 * Time: 17:37
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\DeviseRecusRepository")
 */
class DeviseRecus
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateRecu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomPrenom;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $typePiece;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $NumeroPiece;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $expireLe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Motif;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $DeviseRecus;

    public function getId()
    {
        return $this->id;
    }

    public function getDateRecu()
    {
        return $this->dateRecu;
    }

    public function setDateRecu(\DateTime $dateRecu)
    {
        $this->dateRecu = $dateRecu;

        return $this;
    }

    public function getNomPrenom()
    {
        return $this->nomPrenom;
    }

    public function setNomPrenom(String $nomPrenom)
    {
        $this->nomPrenom = $nomPrenom;

        return $this;
    }

    public function getTypePiece()
    {
        return $this->typePiece;
    }

    public function setTypePiece(String $typePiece)
    {
        $this->typePiece = $typePiece;

        return $this;
    }

    public function getNumeroPiece()
    {
        return $this->NumeroPiece;
    }

    public function setNumeroPiece(String $NumeroPiece)
    {
        $this->NumeroPiece = $NumeroPiece;

        return $this;
    }

    public function getPieceExpire()
    {
        return $this->pieceExpire;
    }

    public function setPieceExpire(DateTime $pieceExpire)
    {
        $this->pieceExpire = $pieceExpire;

        return $this;
    }

    public function getMotif()
    {
        return $this->Motif;
    }

    public function setMotif(String $Motif)
    {
        $this->Motif = $Motif;

        return $this;
    }

    public function getDeviseRecus()
    {
        return $this->DeviseRecus;
    }

    public function setDeviseRecus(string $DeviseRecus)
    {
        $this->DeviseRecus = $DeviseRecus;

        return $this;
    }
}
