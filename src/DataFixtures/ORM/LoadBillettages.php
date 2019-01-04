<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 03/07/2018
 * Time: 18:53
 */

namespace App\DataFixtures\ORM;

use App\Entity\BilletageLignes;
use App\Entity\Billetages;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;



class LoadBillettages extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {/*
        $billet1=$manager->getRepository('App:Billets')->findOneBy(['valeur'=>10000]);
        $billet2=$manager->getRepository('App:Billets')->findOneBy(['valeur'=>5000]);
        $billet3=$manager->getRepository('App:Billets')->findOneBy(['valeur'=>2000]);

        $billet50=$manager->getRepository('App:Billets')->findOneBy(['valeur'=>50]);
        $billet100=$manager->getRepository('App:Billets')->findOneBy(['valeur'=>100]);
        $billet20=$manager->getRepository('App:Billets')->findOneBy(['valeur'=>20]);
        $listss=array([
            array('billet'=>$billet1,'valeurBillet'=>10000, 'nbBillet'=>0)
            ,array('billet'=>$billet2,'valeurBillet'=>5000, 'nbBillet'=>0)
            ,array('billet'=>$billet3,'valeurBillet'=>2000, 'nbBillet'=>0)
        ],
            [
                array('billet'=>$billet50,'valeurBillet'=>$billet50->getValeur(), 'nbBillet'=>0)
                ,array('billet'=>$billet100,'valeurBillet'=>$billet100->getValeur(), 'nbBillet'=>0)
                ,array('billet'=>$billet20,'valeurBillet'=>$billet20->getValeur(), 'nbBillet'=>0)
            ],
            [
            array('billet'=>$billet50,'valeurBillet'=>$billet50->getValeur(), 'nbBillet'=>0)
                ,array('billet'=>$billet100,'valeurBillet'=>$billet100->getValeur(), 'nbBillet'=>0)
            ]
            );

        foreach ($listss as $lists ){
        $billetage=new Billetages($manager);
        $billetage->setDateBillettage(new \DateTime());
        foreach ($lists as $list) {
            $billetageLigne=new BilletageLignes();
            $billetageLigne->setNbBillet($list['nbBillet'])->setValeurBillet($list['valeurBillet'])->setBillet($list['billet']);
            $billetage->addBilletageLigne($billetageLigne);
        }

        $manager->persist($billetage);
        }
        $billetage=new Billetages($manager);
        $billetage->setDateBillettage(new \DateTime());
        $manager->persist($billetage);
        $billetage=new Billetages($manager);
        $billetage->setDateBillettage(new \DateTime());
        $manager->persist($billetage);


        $manager->flush();
*/
    }

    public function getDependencies()
    {
        return array(
            LoadBillet::class,
        );
    }

}