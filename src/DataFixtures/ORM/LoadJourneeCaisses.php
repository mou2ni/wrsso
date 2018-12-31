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
        $utilisateur=$manager->getRepository(Utilisateurs::class)->findOneBy(['login'=>'login']);
        $utilisateur1=$manager->getRepository(Utilisateurs::class)->findOneBy(['login'=>'login1']);

        $caisse=$manager->getRepository(Caisses::class)->findOneBy(['code'=>'KD01']);
        $caisseO=$manager->getRepository(Caisses::class)->findOneBy(['code'=>'KD03']);
        $caisse1=$manager->getRepository(Caisses::class)->findOneBy(['code'=>'KD02']);
        //$utilisateur1->setJourneeCaisseActive($caisseO->)

        $lists = array(['utilisateur' => $utilisateur1, 'caisse' => $caisse, 'statut'=>'T', 'dateOuv'=>new \DateTime()]
        ,['utilisateur' => $utilisateur, 'caisse' => $caisseO, 'statut'=>JourneeCaisses::CLOSE, 'dateOuv'=>new \DateTime()]
        ,['utilisateur' => $utilisateur1, 'caisse' => $caisse1, 'statut'=>JourneeCaisses::INITIAL, 'dateOuv'=>new \DateTime()]);

        foreach ($lists as $list) {
            $enr = new JourneeCaisses($manager);
            $enr->setUtilisateur($list['utilisateur'])
                ->setCaisse($list['caisse'])
                ->setStatut($list['statut'])
                ->setDateOuv($list['dateOuv']);
            if ($enr->getStatut()==JourneeCaisses::CLOSE) {
                //$enr->setMLiquiditeFerm(1000000)->setMSoldeElectFerm(500000)->setMDetteDiversFerm(2000)->setMCreditDiversFerm(1000);
                $journeePrecedente=$enr;
            }
            if ($enr->getStatut()==JourneeCaisses::INITIAL) {
                 $journeeInitial=$enr;
            }

            $manager->persist($enr);
        }
        
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

        $utilisateur->setLastCaisse($journeeOuverte->getCaisse());
        $utilisateur1->setLastCaisse($journeeInitial->getCaisse());

/*
        $utilisateur->setLastCaisse($journeeOuverte->getCaisse());
        $utilisateur1->setLastCaisse($journeeInitial->getCaisse());

        $journeeOuverte->getCaisse()->setStatut(Caisses::OUVERT);
        $journeeInitial->getCaisse()->setStatut(Caisses::OUVERT);
        */


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
    }

    public function getDependencies()
    {
        return array(
            LoadCaisses::class,
            LoadUtilisateurs::class
        );
    }
}