<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 03/07/2018
 * Time: 18:53
 */

namespace App\DataFixtures\ORM;

use App\Entity\Billets;
use App\Entity\Devises;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
//use Doctrine\Common\DataFixtures\DependentFixtureInterface;



class LoadBillet extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $devise1=$manager->getRepository(Devises::class)->findOneBy(['code'=>'EURO']);
        $devise2=$manager->getRepository(Devises::class)->findOneBy(['code'=>'USD']);
        $lists=array (
            /////////////XOF////////////
            array('valeur'=>5,'devise'=>null),
            array('valeur'=>10,'devise'=>null),
            array('valeur'=>25,'devise'=>null),
            array('valeur'=>50,'devise'=>null),
            array('valeur'=>100,'devise'=>null),
            array('valeur'=>200,'devise'=>null),
            array('valeur'=>250,'devise'=>null),
            array('valeur'=>500,'devise'=>null),
            array('valeur'=>1000,'devise'=>null),
            array('valeur'=>2000,'devise'=>null),
            array('valeur'=>5000,'devise'=>null),
            array('valeur'=>10000,'devise'=>null),
            //////////USD////////
            array('valeur'=>1,'devise'=>$devise1),
            array('valeur'=>5,'devise'=>$devise1),
            array('valeur'=>10,'devise'=>$devise1),
            array('valeur'=>20,'devise'=>$devise1),
            array('valeur'=>50,'devise'=>$devise1),
            array('valeur'=>100,'devise'=>$devise1),
            array('valeur'=>200,'devise'=>$devise1),
            //////////EURO////////
            array('valeur'=>1,'devise'=>$devise2),
            array('valeur'=>5,'devise'=>$devise2),
            array('valeur'=>10,'devise'=>$devise2),
            array('valeur'=>20,'devise'=>$devise2),
            array('valeur'=>50,'devise'=>$devise2),
            array('valeur'=>100,'devise'=>$devise2),
            array('valeur'=>200,'devise'=>$devise2),
            array('valeur'=>500,'devise'=>$devise2),
        );

        foreach ($lists as $list) {
            $objet=new Billets();
            $objet->setValeur($list['valeur'])->setDevise($list['devise']);
            $manager->persist($objet);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            LoadDevises::class,
        );
    }
}