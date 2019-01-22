<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 03/07/2018
 * Time: 18:53
 */

namespace App\DataFixtures\ORM;

use App\Entity\Comptes;
use App\Entity\TypeOperationComptables;
use App\Repository\ComptesRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
//use Doctrine\Common\DataFixtures\DependentFixtureInterface;



class LoadTypeOperationComptables extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $compteCharges=$manager->getRepository(Comptes::class)->findCompteCharges();
        $compteProduits=$manager->getRepository(Comptes::class)->findCompteProduits();
        /*foreach ($compteGestions as $compteGestion){

            $lists=array (
                array('libelle'=>$compteGestion->getLibelle(), 'code'=>$compteGestion->getLibelle(), 'compte'=>$compteGestion->numCompte()),
            );
        }*/

        foreach ($compteCharges as $list) {
            $objet=new TypeOperationComptables();
            $objet->setLibelle($list->getIntitule())->setEstCharge(true)->setCompte($list);
            $manager->persist($objet);
        }

        foreach ($compteProduits as $list) {
            $objet=new TypeOperationComptables();
            $objet->setLibelle($list->getIntitule())->setEstCharge(false)->setCompte($list);
            $manager->persist($objet);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            LoadComptes::class
        );
    }
}