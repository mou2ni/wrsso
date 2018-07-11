<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 05/07/2018
 * Time: 05:40
 */

namespace App\DataFixtures\ORM;

use App\Entity\BilletageLignes;
use App\Entity\Comptes;
use App\Entity\DeviseJournees;
use App\Entity\JourneeCaisses;
use App\Entity\Utilisateurs;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadDeviseJournees extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $jouneeCaisses= new JourneeCaisses(); //$manager->getRepository(JourneeCaisses::class)->find(1);
        $devise1=$manager->getRepository(DeviseJournees::class)->find(1);
        $billetageFerm=$manager->getRepository(BilletageLignes::class)->find(2);
        $billetageOuv=$manager->getRepository(BilletageLignes::class)->find(1);
        //$compteEcartCaissier2=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Ecarts caissier 2']);
        $lists = array(['billetageOuv' => $billetageOuv, 'ecartOuv' => 500, 'mCvdAchat' => 6000, 'mCvdVente' => 5000, 'qteAchat' => 10, 'qteVente' => '9',
            'qteOuv' => 10000, 'billetageFerm' => $billetageFerm, 'ecartFerm' => 0, 'qteFerm' => 10000, 'idDevise' => $devise1, 'qteIntercaisse' => 0, 'idJourneeCaisse' => $jouneeCaisses  ]
         );

        foreach ($lists as $list) {
            $enr = new DeviseJournees();
            $enr->setIdBilletOuv($list['billetageOuv'])
                ->setEcartOuv($list['ecartOuv'])
                ->setMCvdAchat($list['mCvdAchat'])
                ->setMCvdVente($list['mCvdVente'])
                ->setQteAchat($list['qteAchat'])
                ->setQteVente($list['qteVente'])
                ->setQteOuv($list['qteOuv'])
                ->setIdBilletFerm($list['billetageFerm'])
                ->setEcartFerm($list['ecartFerm'])
                ->setQteFerm($list['qteFerm'])
                ->setIdDevise($list['idDevise'])
                ->setQteIntercaisse($list['qteIntercaisse'])
                ->setIdJourneeCaisse($list['idJourneeCaisse'])

            ;
            $manager->persist($enr);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(LoadComptes::class);
    }

}