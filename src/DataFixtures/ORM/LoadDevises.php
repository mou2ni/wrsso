<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 03/07/2018
 * Time: 18:53
 */

namespace App\DataFixtures\ORM;

use App\Entity\Clients;
use App\Entity\Devises;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
//use Doctrine\Common\DataFixtures\DependentFixtureInterface;



class LoadDevises extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $lists=array (array('code'=>'USD','libelle'=>'Dollar Americain', 'date'=>new \DateTime(), 'txachat'=>540, 'txvente'=>540),
                            array('code'=>'EURO','libelle'=>'EURO', 'date'=>new \DateTime(), 'txachat'=>655, 'txvente'=>656)
        );

        foreach ($lists as $list) {
            $devise=new Devises();
            $devise->setCode($list['code'])->setLibelle($list['libelle'])->setDateModification($list['date'])->setTxAchat($list['txachat'])->setTxVente($list['txvente']);
            $manager->persist($devise);
        }

        $manager->flush();
    }

}