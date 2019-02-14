<?php
/**
 * Created by PhpStorm.
 * User: Mouni
 * Date: 22/11/2016
 * Time: 10:39
 */

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JournauxComptablesRepository")
 */
class JournauxComptables
{
    const TYP_TRESORERIE='TRS', TYP_ACHAT='ACH', TYP_VENTE='VTE', TYP_OD='OD';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $code;


    /**
     * @ORM\Column(type="string", length=50)
     */
    private $typeJournal=JournauxComptables::TYP_TRESORERIE;

    /**
    * @ORM\Column(type="string")
    */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $compteContrePartie;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transactions", mappedBy="journauxComptable", cascade={"persist"})
     */
    private $transactions;

    /**
     * @ORM\Column(type="integer")
     */
    private $dernierNumPiece=0;

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getCode();
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
     * @return JournauxComptables
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return JournauxComptables
     */
    public function setCode($code)
    {
        $this->code = $code;
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
     * @param mixed $libelle
     * @return JournauxComptables
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompteContrePartie()
    {
        return $this->compteContrePartie;
    }

    /**
     * @param mixed $compteContrePartie
     * @return JournauxComptables
     */
    public function setCompteContrePartie($compteContrePartie)
    {
        $this->compteContrePartie = $compteContrePartie;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDernierNumPiece()
    {
        return $this->dernierNumPiece;
    }

    /**
     * @param mixed $dernierNumPiece
     * @return JournauxComptables
     */
    public function setDernierNumPiece($dernierNumPiece)
    {
        $this->dernierNumPiece = $dernierNumPiece;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTypeJournal()
    {
        return $this->typeJournal;
    }

    /**
     * @param mixed $typeJournal
     * @return JournauxComptables
     */
    public function setTypeJournal($typeJournal)
    {
        $this->typeJournal = $typeJournal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * @param Transactions $transactions
     * @return JournauxComptables
     */
    public function setTransactions($transactions)
    {
        $this->transactions = $transactions;
        return $this;
    }


}