<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 27/06/2018
 * Time: 13:37
 */

namespace App\Controller;

use App\Entity\Comptes;
use App\Entity\Transactions;
use App\Entity\TransactionComptes;
use App\Entity\ParamComptables;
use App\Entity\Utilisateurs;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class GenererEcritureComptaController extends Controller
{
    private $_em;

    /**
     * @var Transactions
     * Les écritures comptables
     */
    private $_trans;

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

        $this->_pc=$this->getDoctrine()->getRepository(ParamComptables::class)->findAll()[0];
        //$this->_pc->ge

    }

    /**
     * @param Transactions $transaction
     * @param Comptes $compte
     * @param $montant
     * @param bool $estCredit
     * @return TransactionComptes
     */
    private function fillTransactionCompte(Transactions $transaction, Comptes $compte, $montant, $estCredit=true){
        $mouvement=new TransactionComptes();
        $mouvement->setTransaction($transaction);
        $mouvement->setCompte($compte);
        $mouvement->setNumCompte($compte->getNumCompte());
        ($estCredit)?$mouvement->setMCredit($montant):$mouvement->setMDebit($montant);

        return $mouvement;
    }



    public function genComptaEcartOuv(Utilisateurs $utilisateur, Comptes $compteCaisse, $montant=0){

        //montant=0 ressortir sans autre écrire
        if($montant==0) return true;
        
        $this->_trans->setUtilisateur($utilisateur)->setLibelle("Ecart d'Ouverture");

        $estCredit=($montant>0);
        //ajout de ligne d'écriture du compte d'opération de la caisse
        $this->_trans->addTransactionComptes($this->fillTransactionCompte($this->_trans, $compteCaisse, $montant, $estCredit));

        //ajout de la ligne d'écriture du compte interne d'écart de caisse
        $this->_trans->addTransactionComptes($this->fillTransactionCompte($this->_trans, $this->_pc->getCompteEcartCaisse(), $montant, !$estCredit));

        $this->_em->persist($this->_trans);
        $this->_em->flush();

        return true;
    }


    

}