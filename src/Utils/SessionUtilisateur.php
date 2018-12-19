<?php
/**
 * Created by PhpStorm.
 * User: houedraogo
 * Date: 18/12/2018
 * Time: 12:22
 */

namespace App\Utils;


use App\Entity\JourneeCaisses;
use Doctrine\ORM\EntityManager;
use Proxies\__CG__\App\Entity\Utilisateurs;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class SessionUtilisateur extends Controller
{
    private $_journeeCaisse;
    private $_utilisateur;

    public function __construct(TokenStorage $secrutityStokenStorage)
    {
        $this->_utilisateur=$secrutityStokenStorage->getToken()->getUser();
        $this->checkSessionUtilisateur();
    }

    private function checkSessionUtilisateur(){
        if (! $this->_utilisateur){
            $this->addFlash('error','Session utilisateur expirée. Merci de vous reconnecter');
            return false;
        }else{
            $this->_journeeCaisse=$this->_utilisateur->getJourneeCaisseActive();
        }
        return true;
    }

    public function getJourneeCaisse(){
        return $this->_journeeCaisse;
    }
    
    public function getUtilisateur(){
        return $this->_utilisateur;
    }

}