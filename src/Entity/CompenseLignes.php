<?php
/**
 * Created by Hamado.
 * User: Hamado,OUEDRAOGO
 * Date: 21/03/2019
 * Time: 15:14
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Callback;

/**
 * @ORM\Entity (repositoryClass="App\Repository\CompenseLignesRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class CompenseLignes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compenses" , inversedBy="compenseLignes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $compense;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SystemTransfert")
     */
    private $systemTransfert;

    /**
     * @ORM\Column(type="float")
     */
    private $mEnvoiAttendu=0;

    /**
     * @ORM\Column(type="float")
     */
    private $mReceptionAttendu=0;

    /**
     * @ORM\Column(type="float")
     */
    private $mEnvoiCompense=0;

    /**
     * @ORM\Column(type="float")
     */
    private $mReceptionCompense=0;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return CompenseLignes
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Compenses
     */
    public function getCompense()
    {
        return $this->compense;
    }

    /**
     * @param Compenses $compense
     * @return CompenseLignes
     */
    public function setCompense($compense)
    {
        $this->compense = $compense;
        return $this;
    }

    /**
     * @return SystemTransfert
     */
    public function getSystemTransfert()
    {
        return $this->systemTransfert;
    }

    /**
     * @param SystemTransfert $systemTransfert
     * @return CompenseLignes
     */
    public function setSystemTransfert($systemTransfert)
    {
        $this->systemTransfert = $systemTransfert;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMEnvoiAttendu()
    {
        return $this->mEnvoiAttendu;
    }

    /**
     * @param mixed $mEnvoiAttendu
     * @return CompenseLignes
     */
    public function setMEnvoiAttendu($mEnvoiAttendu)
    {
        $this->mEnvoiAttendu = $mEnvoiAttendu;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMReceptionAttendu()
    {
        return $this->mReceptionAttendu;
    }

    /**
     * @param mixed $mReceptionAttendu
     * @return CompenseLignes
     */
    public function setMReceptionAttendu($mReceptionAttendu)
    {
        $this->mReceptionAttendu = $mReceptionAttendu;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMEnvoiCompense()
    {
        return $this->mEnvoiCompense;
    }

    /**
     * @param mixed $mEnvoiCompense
     * @return CompenseLignes
     */
    public function setMEnvoiCompense($mEnvoiCompense)
    {
        $this->mEnvoiCompense = $mEnvoiCompense;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMReceptionCompense()
    {
        return $this->mReceptionCompense;
    }

    /**
     * @param mixed $mReceptionCompense
     * @return CompenseLignes
     */
    public function setMReceptionCompense($mReceptionCompense)
    {
        $this->mReceptionCompense = $mReceptionCompense;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param mixed $transaction
     * @return CompenseLignes
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
        return $this;
    }
    
    

}