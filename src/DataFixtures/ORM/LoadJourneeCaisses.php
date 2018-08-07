<?php
/**
 * Created by PhpStorm.
 * User: houedraogo
 * Date: 05/07/2018
 * Time: 06:04
 */

namespace App\DataFixtures\ORM;

use App\Entity\JourneeCaisses;
use App\Entity\Utilisateurs;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Caisses;


class LoadJourneeCaisses extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $utilisateur=$manager->getRepository(Utilisateurs::class)->findOneBy(['login'=>'houedraogo']);
        $caisse=$manager->getRepository(Caisses::class)->findOneBy(['code'=>'KD01']);
        $caisseO=$manager->getRepository(Caisses::class)->findOneBy(['code'=>'KD03']);


        $lists = array(['utilisateur' => $utilisateur, 'idCaisse' => $caisse, 'statut'=>'O', 'dateOuv'=>new \DateTime()]
        ,['utilisateur' => $utilisateur, 'idCaisse' => $caisseO, 'statut'=>'O', 'dateOuv'=>new \DateTime()]);

        foreach ($lists as $list) {
            $enr = new JourneeCaisses();
            $enr->setUtilisateur($list['utilisateur'])
                ->setIdCaisse($list['idCaisse'])
                ->setStatut($list['statut'])
                ->setDateOuv($list['dateOuv']);
            $manager->persist($enr);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(LoadCaisses::class, LoadUtilisateurs::class);
    }
}