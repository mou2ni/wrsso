<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 03/07/2018
 * Time: 18:53
 */

namespace App\DataFixtures\ORM;

use App\Entity\Billets;
use App\Entity\Pays;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
//use Doctrine\Common\DataFixtures\DependentFixtureInterface;



class LoadPays extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $lists=array (
            array('libelle'=>'Burkina Faso', 'zone'=>'UEMOA'),
            array('libelle'=>'Mali', 'zone'=>'UEMOA'),
            array('libelle'=>"Cote d'Ivoire", 'zone'=>'UEMOA'),
            array('libelle'=>'Niger', 'zone'=>'UEMOA'),
            array('libelle'=>'Guinee Conakry', 'zone'=>'UEMOA'),
            array('libelle'=>'Benin', 'zone'=>'UEMOA'),
            array('libelle'=>'Togo', 'zone'=>'UEMOA')
        );

        foreach ($lists as $list) {
            $objet=new Pays();
            $objet->setLibelle($list['libelle'])->setZone($list['zone']);
            $manager->persist($objet);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }
}