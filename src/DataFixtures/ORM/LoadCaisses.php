<?php
/**
 * Created by PhpStorm.
 * User: houedraogo
 * Date: 05/07/2018
 * Time: 06:04
 */

namespace App\DataFixtures\ORM;

use App\Entity\JourneeCaisses;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Caisses;
use App\Entity\Comptes;


class LoadCaisses extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $compteOperationCaisse1=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Opérations Caisse 1']);
        $compteOperationCaisse2=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Opérations Caisse 2']);
        $compteOperationCaisse3=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Opérations Caisse 3']);
        $compteOperationCaisseCMD=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Caisse menu depenses']);
        $compteCvDevise1=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'CV devise Caisse 1']);
        $compteCvDevise2=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'CV devise Caisse 2']);
        $compteCvDevise3=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'CV devise Caisse 3']);

        $compteIntercaisse=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Intercaisse']);

        $idJourneeCaisse=$manager->getRepository(JourneeCaisses::class)->findOneBy(['statut'=>JourneeCaisses::INITIAL]);
        $idJourneeCaisseO=$manager->getRepository(JourneeCaisses::class)->findOneBy(['statut'=>JourneeCaisses::ENCOURS]);


        $lists = array(['libelle' => 'DAPOYA KD01','code' => 'KD01', 'compteOperation' => $compteOperationCaisse1, 'compteCvDevise' => $compteCvDevise1, 'compteIntercaisse' => $compteIntercaisse, 'journeeCaisse' => $idJourneeCaisse]
        ,['libelle' => 'PISSY KD03', 'code' => 'KD03','compteOperation' => $compteOperationCaisse3, 'compteCvDevise' => $compteCvDevise3, 'compteIntercaisse' => $compteIntercaisse, 'journeeCaisse' => $idJourneeCaisseO]
        ,['libelle' => 'Caisse menu depense', 'code' => 'CMD', 'compteOperation' => $compteOperationCaisseCMD, 'compteCvDevise' => null, 'compteIntercaisse' => $compteIntercaisse, 'journeeCaisse' => null]
        ,['libelle' => 'DAPOYA KD02', 'code' => 'KD02', 'compteOperation' => $compteOperationCaisse2, 'compteCvDevise' => $compteCvDevise2, 'compteIntercaisse' => $compteIntercaisse, 'journeeCaisse' => null]
        );

        foreach ($lists as $list) {
            $enr = new Caisses($manager);
            $enr->setLibelle($list['libelle'])->setCode($list['code'])->setCompteOperation($list['compteOperation'])->setCompteCvDevise($list['compteCvDevise'])->setCompteIntercaisse($list['compteIntercaisse']);
            $manager->persist($enr);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            LoadClients::class,
            LoadComptes::class
        );
    }
}