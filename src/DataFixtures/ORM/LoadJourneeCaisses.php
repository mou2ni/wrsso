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
        $caisse=$manager->getRepository(Caisses::class)->findOneBy(['code'=>'KD01']);
        $caisseO=$manager->getRepository(Caisses::class)->findOneBy(['code'=>'KD03']);


        $lists = array(['utilisateur' => $utilisateur, 'caisse' => $caisse, 'statut'=>'T', 'dateOuv'=>new \DateTime()]
        ,['utilisateur' => $utilisateur, 'caisse' => $caisseO, 'statut'=>JourneeCaisses::FERME, 'dateOuv'=>new \DateTime()]);

        foreach ($lists as $list) {
            $enr = new JourneeCaisses();
            $enr->setUtilisateur($list['utilisateur'])
                ->setCaisse($list['caisse'])
                ->setStatut($list['statut'])
                ->setDateOuv($list['dateOuv']);
            if ($enr->getStatut()==JourneeCaisses::FERME) {
                $enr->setMLiquiditeFerm(1000000)->setMSoldeElectFerm(500000)->setMDetteDivers(2000)->setMCreditDivers(1000);
                $journeePrecedente=$enr;
            }
            $manager->persist($enr);
        }
        
        $journeeOuverte=new JourneeCaisses();
        $journeeOuverte->setCaisse($journeePrecedente->getCaisse())
            ->setUtilisateur($journeePrecedente->getUtilisateur())
            ->setMLiquiditeOuv($journeePrecedente->getMLiquiditeFerm())
            ->setMSoldeElectOuv($journeePrecedente->getMSoldeElectFerm())
            ->setMDetteDivers($journeePrecedente->getMDetteDivers())
            ->setMCreditDivers($journeePrecedente->getMCreditDivers())
            ->setStatut(JourneeCaisses::OUVERT)
            ->setJourneePrecedente($journeePrecedente)
            ->setDateOuv(new \DateTime());

        $utilisateur->setJourneeCaisseActive($journeeOuverte);

        $manager->persist($journeeOuverte);
        $manager->persist($utilisateur);
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            LoadCaisses::class,
            LoadUtilisateurs::class
        );
    }
}