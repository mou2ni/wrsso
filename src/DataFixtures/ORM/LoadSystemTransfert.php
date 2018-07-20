<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 03/07/2018
 * Time: 18:53
 */

namespace App\DataFixtures\ORM;

use App\Entity\Billets;
use App\Entity\SystemTransfert;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
//use Doctrine\Common\DataFixtures\DependentFixtureInterface;



class LoadSystemTransfert extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $lists=array (
            array('libelle'=>'Western Union'),
            array('libelle'=>'MonneyGram'),
            array('libelle'=>'RIA'),
            array('libelle'=>'SIGUE'),
            array('libelle'=>'JUBA'),
            array('libelle'=>'Small World'),
            array('libelle'=>'Rapid Transfert'),


        );

        foreach ($lists as $list) {
            $objet=new SystemTransfert();
            $objet->setLibelle($list['libelle']);
            $manager->persist($objet);
        }

        $manager->flush();
    }

}