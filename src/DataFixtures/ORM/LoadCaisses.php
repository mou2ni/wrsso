<?php
/**
 * Created by PhpStorm.
 * User: houedraogo
 * Date: 05/07/2018
 * Time: 06:04
 */

namespace App\DataFixtures\ORM;

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
        $compteOperationCaisse3=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Caisse menu depenses']);
        $compteCvDevise1=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'CV devise Caisse 1']);
        $compteCvDevise2=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'CV devise Caisse 2']);

        $lists = array(['libelle' => 'DAPOYA KD01','code' => 'KD01', 'idCompteOperation' => $compteOperationCaisse1, 'compteCvDevise' => $compteCvDevise1]
        ,['libelle' => 'PISSY KD03', 'code' => 'KD03','idCompteOperation' => $compteOperationCaisse2, 'compteCvDevise' => $compteCvDevise2]
        ,['libelle' => 'Caisse menu depense', 'code' => 'CMD', 'idCompteOperation' => $compteOperationCaisse3, 'compteCvDevise' => $compteCvDevise2]
        );

        foreach ($lists as $list) {
            $enr = new Caisses();
            $enr->setLibelle($list['libelle'])->setCode($list['code'])->setIdCompteOperation($list['idCompteOperation'])
                ->setCompteCvDevise($list['compteCvDevise'])->setStatus(Caisses::FERME);
            $manager->persist($enr);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(LoadComptes::class);
    }
}