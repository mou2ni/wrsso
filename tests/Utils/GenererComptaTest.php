<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 12/07/2018
 * Time: 17:28
 */

namespace App\Tests\Utils;

use App\Entity\Caisses;
use App\Entity\Comptes;
use App\Entity\ParamComptables;
use App\Entity\Transactions;
use App\Entity\TransactionComptes;
use App\Entity\Utilisateurs;

use App\Utils\GenererCompta;

use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

class GenererComptaTest extends TestCase
{
    /*private $em;
    private $utilisateur;
    private $caisse;
    private $paramComptable;
    private $genererCompta;*/

    public function __construct(ObjectManager $objectManager, EntityManager $entityManager)
    {
        /*$this->em=$entityManager;
        $this->om=$objectManager;
        $this->transactions= new ArrayCollection();*/

    }

    private function setParamTest()
    {
        //// A ENVOYER PAR L APPELANT
        /*
        $this->utilisateur=$this->em->getRepository(Utilisateurs::class)->findOneBy(['login'=>'asanou']);
        $this->caisse=$this->em->getRepository(Caisses::class)->findOneBy(['libelle'=>'PISSY-Caisse 1']);
        $this->paramComptable=$this->em->->getRepository(ParamComptables::class)->findOneBy(['codeStructure'=>'YESBO']);
        */



    }

    public function testGenComptaEcart()
    {
        /*

        //$transactions=array();
        $this->setParamTest();
        $genererCompta=new GenererCompta();

        /////////////////////////////// ECART OUVERTURE DE CAISSE : RETROUR LA TRANSACTION //////////////////////////

        $genererCompta->genComptaEcart($this->utilisateur,$this->caisse, 'Ecart ouverture', 2000);

        $genererCompta->genComptaEcart($utilisateur,$caisse, 'Ecart ouverture', -1000);
        ///////////////////////////////////FIN ECART OUVERTURE ////////////////////////////////////////////////////////////////////


        /////////////////////////////// DEPOT ET RETRAIT :  RETROUR LA TRANSACTION //////////////////////////

        $compteClient=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['intitule'=>'OUEDRAOGO HAMADO - Ordinaire']);
        $this->genComptaDepot($utilisateur,$caisse,$compteClient, 'Depot Cash par LMM', 3000000);

        $this->genComptaRetrait($utilisateur,$caisse,$compteClient, 'Retrait Cash par LMM', 100000);

        ///////////////////////////////////FIN


        /////////////////////////////// DEPENSES ET RECETTES :  RETROUR LA TRANSACTION //////////////////////////
        $caisseMD=$this->getDoctrine()->getRepository(Caisses::class)->findOneBy(['libelle'=>'Caisse menu depense']);

        $compteCharge=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['intitule'=>'Charges diverses']);
        $this->genComptaDepenses($utilisateur,$caisseMD,$compteCharge, 'Achats Internet', 50000);

        $compteRecette=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['intitule'=>'Produits divers']);
        $this->genComptaRecettes($utilisateur,$caisseMD,$compteRecette, 'Vente antivirus', 60000);

        ///////////////////////////////////FIN

        /////////////////////////////// COMPENSES //////////////////////////

        $this->genComptaCompense($utilisateur,$caisse,$paramComptable,400000);
        $this->genComptaCompense($utilisateur,$caisse,$paramComptable,-300000);

        ///////////////////////////////////FIN

        /////////////////////////////// DEVISE //////////////////////////

        $this->genComptaCvDevise($utilisateur,$caisse,671000);
        $this->genComptaCvDevise($utilisateur,$caisse,-661000);

        ///////////////////////////////////FIN

        /////////////////////////////// INTER CAISSE //////////////////////////

        $this->genComptaIntercaisse($utilisateur,$caisse,$paramComptable,250000);
        $this->genComptaIntercaisse($utilisateur,$caisse,$paramComptable,-200000);

        ///////////////////////////////////FIN

        /////////////////////////////// FERMETURE CAISSE //////////////////////////

        $this->genComptaFermeture($utilisateur,$caisse,$paramComptable,100000,200000,300000,2000);
        $this->genComptaFermeture($utilisateur,$caisse,$paramComptable,-50000,-100000,-150000,-1000);

        ///////////////////////////////////FIN

        /////////////////////////////// SALAIRES //////////////////////////

        $listSalaires=new ArrayCollection();
        $listSalaires->add(['compte'=>$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['intitule'=>'OUEDRAOGO HAMADO - Salaire']),'montant'=>500000]);
        $listSalaires->add(['compte'=>$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['intitule'=>'OUEDRAOGO Moumouni - Salaire']),'montant'=>900000]);

        $this->genComptaSalaireNet($utilisateur,$paramComptable,$listSalaires,'Salaire du mois de Juillet 2018', 1400000);


        ///////////////////////////////////FIN

        if (! $this->getE()) return $this->render( 'comptMainTest.html.twig',['transactions'=>$this->transactions]);
        else { $transaction=new Transactions();
            $transaction->setLibelle($this->getE());
            //$transaction->setTransactionComptes($this->transactions);
            return $this->render( 'comptMainTest.html.twig',['transactions'=>[$transaction]]);
        }

    }*/
    }

}
