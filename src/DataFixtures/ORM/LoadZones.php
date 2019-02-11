<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 03/07/2018
 * Time: 18:53
 */

namespace App\DataFixtures\ORM;

use App\Entity\Zones;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;



class LoadZones extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $lists=array (
            array('libelle'=>'ZONE UEMOA', 'code'=>'UEMOA', 'ordre'=>1),
            array('libelle'=>'ZONE AUTRES CEDEAO', 'code'=>'CEDEAO', 'ordre'=>2),
                  ////////CEMAC/////
            array('libelle'=>'ZONE CEMAC', 'code'=>'CEMAC', 'ordre'=>3),
               /////////////MAGHREB/////////////
            array('libelle'=>'ZONE MAGREB', 'code'=>'MAGHREB', 'ordre'=>4),
            ////////////Afrique du Sud//////////
            array('libelle'=>'ZONE AFRIQUE DU SUD', 'code'=>'AFRIQUESUD', 'ordre'=>5),
            ////////USA///////////////
            array('libelle'=>'ZONE USA', 'code'=>'USA', 'ordre'=>6),
            ///////////EUROPE/////////////
            array('libelle'=>'ZONE EUROPE', 'code'=>'EUROPE', 'ordre'=>7),
            ////////INDE///////////////
            array('libelle'=>'ZONE INDE', 'code'=>'INDE', 'ordre'=>8),
            ////////USA///////////////
            array('libelle'=>'ZONE CHINE', 'code'=>'CHINE', 'ordre'=>9),
            ////////USA///////////////
            array('libelle'=>'ZONE BRESIL', 'code'=>'BRESIL', 'ordre'=>10),
            ////////USA///////////////
            array('libelle'=>'ZONE AUTRES', 'code'=>'AUTRES', 'ordre'=>11),


        );

        foreach ($lists as $list) {
            $objet=new Zones();
            $objet->setLibelle($list['libelle'])->setCode($list['code'])->setOrdre($list['ordre']);
            $manager->persist($objet);
        }

        $manager->flush();
    }

}