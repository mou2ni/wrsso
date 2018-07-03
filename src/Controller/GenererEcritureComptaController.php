<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 27/06/2018
 * Time: 13:37
 */

namespace App\Controller;

use App\Entity\Transactions;
use App\Entity\TransactionComptes;
use App\Entity\ParamComptables;

use Symfony\Bridge\Doctrine;
use Doctrine\Common;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\DateTime;


class GenererEcritureComptaController extends Controller
{
    private $_em;

    /**
     * @var Transactions
     * Les écritures comptables
     */
    private $_trans;

    /**
     * @var array
     * Les lignes d'écritures
     */

    private $tc;

    /**
     * @var
     *
     * Le plan des comptes
     */
    private $_pc; //Plan comptable

    public function __construct()
    {
        $this->_em= $this->getDoctrine()->getManager();
        $this->_trans = new Transactions();
        $this->_trans->setDateTransaction(new \DateTime());
        $this->tc= new \Doctrine\Common\Collections\ArrayCollection();
        $this->_pc=$this->_em->getRepository(ParamComptables::class)->find();
        //$this->_tc->setIdTrans($this->_trans);
    }



    private function addTc(TransactionComptes $transactionCompte){
        $this->tc[]=$transactionCompte;
        return $this;
    }



    public function ecartOuverture($idUtilisateur, $compteCaisse, $montant=0){

        //montant=0 ressortir sans autre écrire
        if($montant==0) return true;

        $this->_trans->setIdUtilisateur($idUtilisateur)->setLibelle("Ecart d'Ouverture");

    }


    

}