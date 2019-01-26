<?php
/**
 * Created by Hamado.
 * Date: 21/01/2019
 * Time: 06:39
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeOperationComptablesRepository")
 */
class TypeOperationComptables
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $libelle;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptes", cascade={"persist"})
     */
    private $compte;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return TypeOperationComptables
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return TypeOperationComptables
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompte()
    {
        return $this->compte;
    }

    /**
     * @param mixed $compte
     * @return TypeOperationComptables
     */
    public function setCompte($compte)
    {
        $this->compte = $compte;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEstCharge()
    {
        return $this->estCharge;
    }

    /**
     * @param mixed $estCharge
     * @return TypeOperationComptables
     */
    public function setEstCharge($estCharge)
    {
        $this->estCharge = $estCharge;
        return $this;
    }

}