<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 03/07/2018
 * Time: 18:53
 */

namespace App\DataFixtures\ORM;

use App\Entity\Billets;
use App\Entity\SystemElects;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
//use Doctrine\Common\DataFixtures\DependentFixtureInterface;



class LoadSystemElects extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $lists=array (
            array('libelle'=>'Orange Money'),
            array('libelle'=>'Mobicash'),
            array('libelle'=>'Coris Money'),
            array('libelle'=>'coris Cash'),
            array('libelle'=>'Cannal +'),
            array('libelle'=>'Nana Express'),
            array('libelle'=>'Sap Sap'),
            array('libelle'=>'Flash'),
            array('libelle'=>'Rapid Transfert'),
            array('libelle'=>'Wari'),
        );

        foreach ($lists as $list) {
            $objet=new SystemElects();
            $objet->setLibelle($list['libelle']);
            $manager->persist($objet);
        }

        $manager->flush();
    }

}