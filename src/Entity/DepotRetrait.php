<?php
/**
 * Created by PhpStorm.
 * User: Mouni
 * Date: 22/11/2016
 * Time: 10:39
 */

namespace App\Entity;



class DepotRetrait
{

    //private $compte;
    private $numCompte;
    private $mDebit;
    private $mCredit;
    private $libele;

    /**
     * @return mixed
     */
    public function getNumCompte()
    {
        return $this->numCompte;
    }

    /**
     * @param mixed $numCompte
     * @return DepotRetrait
     */
    public function setNumCompte($numCompte)
    {
        $this->numCompte = $numCompte;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMDebit()
    {
        return $this->mDebit;
    }

    /**
     * @param mixed $mDebit
     * @return DepotRetrait
     */
    public function setMDebit($mDebit)
    {
        $this->mDebit = $mDebit;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMCredit()
    {
        return $this->mCredit;
    }

    /**
     * @param mixed $mCredit
     * @return DepotRetrait
     */
    public function setMCredit($mCredit)
    {
        $this->mCredit = $mCredit;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLibele()
    {
        return $this->libele;
    }

    /**
     * @param mixed $libele
     * @return DepotRetrait
     */
    public function setLibele($libele)
    {
        $this->libele = $libele;
        return $this;
    }


}