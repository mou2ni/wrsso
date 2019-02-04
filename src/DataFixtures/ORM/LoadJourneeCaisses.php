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
        $utilisateur=$manager->getRepository(Utilisateurs::class)->findOneBy(['login'=>'ganou']);
        $utilisateur1=$manager->getRepository(Utilisateurs::class)->findOneBy(['login'=>'admin']);

        $caisse=$manager->getRepository(Caisses::class)->findOneBy(['code'=>'CMD']);
        $caisse1=$manager->getRepository(Caisses::class)->findOneBy(['code'=>'APPRO']);
        //$caisse1=$manager->getRepository(Caisses::class)->findOneBy(['code'=>'KD02']);
        //$utilisateur1->setJourneeCaisseActive($caisseO->)

        $lists = array(['utilisateur' => $utilisateur, 'caisse' => $caisse, 'statut'=>JourneeCaisses::ENCOURS]
        ,['utilisateur' => $utilisateur1, 'caisse' => $caisse1, 'statut'=>JourneeCaisses::ENCOURS]);
        //,['utilisateur' => $utilisateur1, 'caisse' => $caisse1, 'statut'=>JourneeCaisses::INITIAL, 'dateOuv'=>new \DateTime()]);

        foreach ($lists as $list) {
            $enr = new JourneeCaisses($manager);
            $enr->setUtilisateur($list['utilisateur'])
                ->setCaisse($list['caisse'])
                ->setStatut($list['statut'])
                ->setDateOuv($list['dateOuv']);

            $manager->persist($enr);
        }
        $caisse->setStatut(Caisses::OUVERT);
        $utilisateur->setLastCaisse($caisse);
        $utilisateur1->setLastCaisse($caisse1);
        $caisse1->setStatut(Caisses::OUVERT);
        $manager->persist($utilisateur);
        $manager->persist($utilisateur1);
        $manager->persist($caisse);
        $manager->persist($caisse1);
        $manager->flush();
/*
        $journeeOuverte=new JourneeCaisses($manager);
        $journeeOuverte->setCaisse($journeePrecedente->getCaisse())
            ->setUtilisateur($journeePrecedente->getUtilisateur())
            ->setMLiquiditeOuv($journeePrecedente->getMLiquiditeFerm())
            ->setMSoldeElectOuv($journeePrecedente->getMSoldeElectFerm())
            ->setMDetteDiversOuv($journeePrecedente->getMDetteDiversFerm())
            ->setMCreditDiversOuv($journeePrecedente->getMCreditDiversFerm())
            ->setStatut(JourneeCaisses::ENCOURS)
            ->setJourneePrecedente($journeePrecedente)
            ->setDateOuv(new \DateTime());

        //$utilisateur->setLastCaisse($journeeOuverte->getCaisse());
        //$utilisateur1->setLastCaisse($journeeInitial->getCaisse());

/*
        $utilisateur->setLastCaisse($journeeOuverte->getCaisse());
        $utilisateur1->setLastCaisse($journeeInitial->getCaisse());

        $journeeOuverte->getCaisse()->setStatut(Caisses::OUVERT);
        $journeeInitial->getCaisse()->setStatut(Caisses::OUVERT);



        $manager->persist($journeeOuverte);
        $manager->persist($utilisateur);
        $manager->persist($utilisateur1);
        $manager->flush();
        //$journeeOuverte->getCaisse()->setJourneeOuverteId($journeeOuverte->getId());
        //$utilisateur->setJourneeCaisseActiveId($journeeOuverte->getId());
        //$utilisateur1->setJourneeCaisseActiveId($journeeInitial->getId());
        //$manager->persist($journeeOuverte);
        //$manager->persist($utilisateur);
        //$manager->persist($utilisateur1);
       // $manager->flush();
        */
    }

    public function getDependencies()
    {
        return array(
            LoadCaisses::class,
            LoadUtilisateurs::class
        );
    }
}