<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 20/07/2018
 * Time: 15:24
 */

namespace App\Controller;

use App\Utils\GenererCompta;
use App\Entity\Caisses;
use App\Entity\Comptes;
use App\Entity\ParamComptables;
use App\Entity\Utilisateurs;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;




/**
 * @Route("/test")
 */

class GenererComptaTest extends Controller
{

    /**
     * @Route("/gencompta", name="test_gen_compta", methods="GET|POST")
     */
    public function genererComptaTest()
    {
        $genererCompta=new GenererCompta($this->getDoctrine()->getManager());

        $utilisateur=$this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['login'=>'login']);
        $caisse=$this->getDoctrine()->getRepository(Caisses::class)->findOneBy(['code'=>'KD01']);
        $paramComptable=$this->getDoctrine()->getRepository(ParamComptables::class)->findOneBy(['codeStructure'=>'YESBO']);

        /////////////////////////////// ECART OUVERTURE DE CAISSE : RETROUR LA TRANSACTION //////////////////////////


        if (!$genererCompta->genComptaEcart($utilisateur,$caisse, 'Ecart ouverture', 2000)) return $this->render( 'comptMainTest.html.twig',['transactions'=>[$genererCompta->getTransactions()]]);
        if (!$genererCompta->genComptaEcart($utilisateur,$caisse, 'Ecart ouverture', -1000)) return $this->render( 'comptMainTest.html.twig',['transactions'=>[$genererCompta->getTransactions()]]);
        ///////////////////////////////////FIN ECART OUVERTURE ////////////////////////////////////////////////////////////////////


        /////////////////////////////// DEPOT ET RETRAIT :  RETROUR LA TRANSACTION //////////////////////////

        $compteClient=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['intitule'=>'OUEDRAOGO HAMADO - Ordinaire']);

        if(!$genererCompta->genComptaDepot($utilisateur,$caisse,$compteClient, 'Depot Cash par LMM', 3000000)) return $this->render( 'comptMainTest.html.twig',['transactions'=>[$genererCompta->getTransactions()]]);

        if(!$genererCompta->genComptaRetrait($utilisateur,$caisse,$compteClient, 'Retrait Cash par LMM', 100000)) return $this->render( 'comptMainTest.html.twig',['transactions'=>[$genererCompta->getTransactions()]]);

        ///////////////////////////////////FIN


        /////////////////////////////// DEPENSES ET RECETTES :  RETROUR LA TRANSACTION //////////////////////////
        $caisseMD=$this->getDoctrine()->getRepository(Caisses::class)->findOneBy(['code'=>'CMD']);

        $compteCharge=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['intitule'=>'Charges diverses']);
        if(!$genererCompta->genComptaDepenses($utilisateur,$caisseMD,$compteCharge, 'Achats Internet', 50000)) return $this->render( 'comptMainTest.html.twig',['transactions'=>[$genererCompta->getTransactions()]]);

        $compteRecette=$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['intitule'=>'Produits divers']);
        if(!$genererCompta->genComptaRecettes($utilisateur,$caisseMD,$compteRecette, 'Vente antivirus', 60000)) return $this->render( 'comptMainTest.html.twig',['transactions'=>[$genererCompta->getTransactions()]]);

        ///////////////////////////////////FIN

        /////////////////////////////// COMPENSES //////////////////////////

        //if(!$genererCompta->genComptaCompense($utilisateur,$caisse,$paramComptable,400000)) return $this->render( 'comptMainTest.html.twig',['transactions'=>[$genererCompta->getTransactions()]]);
        //if(!$genererCompta->genComptaCompense($utilisateur,$caisse,$paramComptable,-300000)) return $this->render( 'comptMainTest.html.twig',['transactions'=>[$genererCompta->getTransactions()]]);

        ///////////////////////////////////FIN

        /////////////////////////////// DEVISE //////////////////////////

        //if(!$genererCompta->genComptaCvDevise($utilisateur,$caisse,671000)) return $this->render( 'comptMainTest.html.twig',['transactions'=>[$genererCompta->getTransactions()]]);
        //if(!$genererCompta->genComptaCvDevise($utilisateur,$caisse,-661000)) return $this->render( 'comptMainTest.html.twig',['transactions'=>[$genererCompta->getTransactions()]]);

        ///////////////////////////////////FIN

        /////////////////////////////// INTER CAISSE //////////////////////////

        if(!$genererCompta->genComptaIntercaisse($utilisateur,$caisse,$paramComptable,250000)) return $this->render( 'comptMainTest.html.twig',['transactions'=>[$genererCompta->getTransactions()]]);
        if(!$genererCompta->genComptaIntercaisse($utilisateur,$caisse,$paramComptable,-200000)) return $this->render( 'comptMainTest.html.twig',['transactions'=>[$genererCompta->getTransactions()]]);

        ///////////////////////////////////FIN

        /////////////////////////////// FERMETURE CAISSE //////////////////////////

        if(!$genererCompta->genComptaFermeture($utilisateur,$caisse,$paramComptable,100000,200000,300000,2000)) return $this->render( 'comptMainTest.html.twig',['transactions'=>[$genererCompta->getTransactions()]]);
        if(!$genererCompta->genComptaFermeture($utilisateur,$caisse,$paramComptable,-50000,-100000,-150000,-1000)) return $this->render( 'comptMainTest.html.twig',['transactions'=>[$genererCompta->getTransactions()]]);

        ///////////////////////////////////FIN

        /////////////////////////////// SALAIRES //////////////////////////

        $listSalaires=new ArrayCollection();
        $listSalaires->add(['compte'=>$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['intitule'=>'OUEDRAOGO HAMADO - Salaire']),'montant'=>500000]);
        $listSalaires->add(['compte'=>$this->getDoctrine()->getRepository(Comptes::class)->findOneBy(['intitule'=>'OUEDRAOGO Moumouni - Salaire']),'montant'=>900000]);

        if(!$genererCompta->genComptaSalaireNet($utilisateur,$paramComptable,$listSalaires,'Salaire du mois de Juillet 2018', 1400000)) return $this->render( 'comptMainTest.html.twig',['transactions'=>[$genererCompta->getTransactions()]]);

        ///////////////////////////////////FIN

        return $this->render( 'comptMainTest.html.twig',['transactions'=>$genererCompta->getTransactions()]);

    }

}