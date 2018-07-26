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



class LoadBillettages extends Fixture  implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $billetageLigne1=new BilletageLignes();
        $billetageLigne1->setValeurBillet(50)->setNbBillet(1);
        $billetageLigne2=new BilletageLignes();
        $billetageLigne2->setValeurBillet(100)->setNbBillet(2);

        $billetageLigne10=new BilletageLignes();
        $billetageLigne10->setValeurBillet(10)->setNbBillet(1);
        $billetageLigne20=new BilletageLignes();
        $billetageLigne20->setValeurBillet(50)->setNbBillet(2);

        $lists=array (
            array('dateBillettage'=>new \DateTime(),'billetageLigne1'=>$billetageLigne1, 'billetageLigne2'=>$billetageLigne2)
            ,array('dateBillettage'=>new \DateTime(),'billetageLigne1'=>$billetageLigne10, 'billetageLigne2'=>$billetageLigne20)
        );

        foreach ($lists as $list) {
            $objet=new Billetages();
            $objet->setDateBillettage($list['dateBillettage']);
            $objet->addBilletageLignes($list['billetageLigne1']);
            $objet->addBilletageLignes($list['billetageLigne2']);
            $manager->persist($objet);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(LoadBillet::class);
    }

}