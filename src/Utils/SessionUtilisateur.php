<?php
/**
 * Created by PhpStorm.
 * User: houedraogo
 * Date: 18/12/2018
 * Time: 12:22
 */

namespace App\Utils;


use App\Entity\Caisses;
use App\Entity\JourneeCaisses;
use App\Entity\Utilisateurs;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class SessionUtilisateur extends Controller
{
    private $journeeCaisse=null;
    private $utilisateur;
    private $lastCaisse;
    //private $paramComptable;

    public function __construct(TokenStorage $secrutityStokenStorage)
    {
        $this->utilisateur=$secrutityStokenStorage->getToken()->getUser();
        $this->checkSessionUtilisateur();
        //$this->paramComptable=$this->getDoctrine()->getRepository(ParamComptables::class)->findOneBy(['codeStructure'=>'YESBO']);
    }

    private function checkSessionUtilisateur(){
        if (! $this->utilisateur){
            return false;
        }else{
            $this->lastCaisse=$this->utilisateur->getLastCaisse();
            if ($this->lastCaisse) $this->journeeCaisse=$this->lastCaisse->getLastJournee();
        }
        /*if (! $this->_utilisateur){
            return false;
        }else{
            $this->_journeeCaisse=$this->_utilisateur->getJourneeCaisseActive();
        }
        return true;*/
    }

    public function getLastCaisse(){
        return $this->lastCaisse;
    }
    
    public function getUtilisateur(){
        return $this->utilisateur;
    }

    public function getJourneeCaisse(){
        return $this->journeeCaisse;
    }

    public function setUtilisateur(Utilisateurs $utilisateur){
        $this->utilisateur=$utilisateur;
        return $this;
    }

    public function setLastCaisse(Caisses $caisse){
        $this->lastCaisse=$caisse;
        return $this;
    }

    public function setJourneeCaisse(JourneeCaisses $journeeCaisse){
        $this->journeeCaisse=$journeeCaisse;
    }

    //public function getParamComptable(){
        //return $this->getDoctrine()->getRepository(ParamComptables::class)->findOneBy(['codeStructure'=>'YESBO']);
    //}

}