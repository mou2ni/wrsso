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
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;



class LoadTypeOperationComptables extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $compteCharges=$manager->getRepository(Comptes::class)->findCompteCharges();
        $compteProduits=$manager->getRepository(Comptes::class)->findCompteProduits();

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
            LoadClients::class,
            LoadComptes::class,
            LoadJournauxComptables::class,
        );
    }

}