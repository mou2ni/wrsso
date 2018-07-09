<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 03/07/2018
 * Time: 18:53
 */

namespace App\DataFixtures\ORM;

use App\Entity\Billets;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
//use Doctrine\Common\DataFixtures\DependentFixtureInterface;



class LoadBillet extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $lists=array (
            array('valeur'=>5),
            array('valeur'=>10),
            array('valeur'=>25),
            array('valeur'=>50),
            array('valeur'=>100),
            array('valeur'=>200),
            array('valeur'=>250),
            array('valeur'=>500),
            array('valeur'=>1000),
            array('valeur'=>2000),
            array('valeur'=>5000),
            array('valeur'=>10000)
        );

        foreach ($lists as $list) {
            $objet=new Billets();
            $objet->setValeur($list['valeur']);
            $manager->persist($objet);
        }

        $manager->flush();
    }

}