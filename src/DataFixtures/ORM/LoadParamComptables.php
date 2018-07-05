<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 04/07/2018
 * Time: 14:19
 */

namespace App\DataFixtures\ORM;

use App\Entity\ParamComptables;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Comptes;


class LoadParamComptables extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $compteIntercaisse=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Intercaisse']);
        $compteContreValeurDevise=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Contre Valeur Devises']);
        $compteCompense=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Compense']);
        $compteChargeSalaireNet=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Charges Salaires']);
        $compteEcartCaisse=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Ecarts de caisse']);

        $enr=new ParamComptables();
        $enr->setCodeStructure('YESBO')->setCompteChargeSalaireNet($compteChargeSalaireNet)->setCompteCompense($compteCompense)
            ->setCompteContreValeurDevise($compteContreValeurDevise)->setCompteIntercaisse($compteIntercaisse)->setCompteEcartCaisse($compteEcartCaisse);

        $manager->persist($enr);
        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 30;
    }

}