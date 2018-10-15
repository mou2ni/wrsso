<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 26/07/2018
 * Time: 13:03
 */

namespace App\DataFixtures\ORM;

use App\Entity\Billetages;
use App\Entity\Billets;
use App\Entity\DeviseJournees;
use App\Entity\Devises;
use App\Entity\JourneeCaisses;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadDeviseJournees extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $idJourneeCaisse=$manager->getRepository(JourneeCaisses::class)->findOneBy(['statut'=>'O']);
        //$idJourneeCaisseO=$manager->getRepository(JourneeCaisses::class)->findOneBy(['statut'=>JourneeCaisses::OUVERT]);
        $usd=$manager->getRepository(Devises::class)->findOneBy(['code'=>'USD']);
        $euro=$manager->getRepository(Devises::class)->findOneBy(['code'=>'EURO']);

        /*$billet50=$manager->getRepository(Billets::class)->findOneBy(['valeurBillet'=>50]);
        $billet100=$manager->getRepository(Billets::class)->findOneBy(['valeurBillet'=>100]);

        $idBilletOuv1=$manager->getRepository(Billetages::class)->findOneBy(['valeurTotal'=>250]);
        $idBilletOuv2=$manager->getRepository(Billetages::class)->findOneBy(['valeurTotal'=>110]);*/

        $billetage=$manager->getRepository(Billetages::class)->findAll();


        $lists = array(['journeeCaisse' =>  $idJourneeCaisse, 'devise' => $usd, 'billetOuv'=>$billetage[1]]
            ,['journeeCaisse' => $idJourneeCaisse, 'devise' => $euro, 'billetOuv'=>$billetage[2]]
        );

        foreach ($lists as $list) {
            $enr = new DeviseJournees();
            $enr->setJourneeCaisse($list['journeeCaisse'])
                ->setDevise($list['devise'])
                ->setBilletOuv($list['billetOuv']);
            $manager->persist($enr);
        }

       /* $jouneeCaisses= new JourneeCaisses(); //$manager->getRepository(JourneeCaisses::class)->find(1);
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
*/
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            LoadDevises::class,
            LoadBillettages::class,
            LoadJourneeCaisses::class
        );
    }


}