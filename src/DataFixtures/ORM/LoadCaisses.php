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
        $reqCompte = $manager->getRepository(Comptes::class);
        $compteIntercaisse=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Intercaisse']);
        $compteCompense=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Compense']);
        /*
        $compteOperationCaisse1=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Opérations Caisse 1']);
        $compteOperationCaisse2=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Opérations Caisse 2']);
        $compteOperationCaisse3=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Opérations Caisse 3']);
        $compteOperationCaisseCMD=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Caisse menu depenses']);
        $compteCvDevise1=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'CV devise Caisse 1']);
        $compteCvDevise2=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'CV devise Caisse 2']);
        $compteCvDevise3=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'CV devise Caisse 3']);


        $idJourneeCaisse=$manager->getRepository(JourneeCaisses::class)->findOneBy(['statut'=>JourneeCaisses::INITIAL]);
        $idJourneeCaisseO=$manager->getRepository(JourneeCaisses::class)->findOneBy(['statut'=>JourneeCaisses::ENCOURS]);
        */

        $lists = array(
            ['libelle' => 'DAPOYA KD00',
                'code' => 'KD00',
                'compteOperation' => $reqCompte->findOneBy(['intitule'=>'Opérations Caisse 0']),
                'compteCvDevise' => $reqCompte->findOneBy(['intitule'=>'CV devise Caisse 0']),
                'compteIntercaisse' => $compteIntercaisse,
                'compteAttenteCompense' => $compteCompense,
                'journeeCaisse' => null,
                'typeCaisse'=>Caisses::GUICHET
            ]
        ,['libelle' => 'DAPOYA KD01',
                'code' => 'KD01',
                'compteOperation' => $reqCompte->findOneBy(['intitule'=>'Opérations Caisse 1']),
                'compteCvDevise' => $reqCompte->findOneBy(['intitule'=>'CV devise Caisse 1']),
                'compteIntercaisse' => $compteIntercaisse,
                'compteAttenteCompense' => $compteCompense,
                'journeeCaisse' => null,
                'typeCaisse'=>Caisses::GUICHET
            ]
        ,['libelle' => 'PISSY KD02',
                'code' => 'KD02',
                'compteOperation' => $reqCompte->findOneBy(['intitule'=>'Opérations Caisse 2']),
                'compteCvDevise' => $reqCompte->findOneBy(['intitule'=>'CV devise Caisse 2']),
                'compteIntercaisse' => $compteIntercaisse,
                'compteAttenteCompense' => $compteCompense,
                'journeeCaisse' => null,
                'typeCaisse'=>Caisses::GUICHET
            ]
        ,['libelle' => 'PISSY KD03',
                'code' => 'KD03',
                'compteOperation' => $reqCompte->findOneBy(['intitule'=>'Opérations Caisse 3']),
                'compteCvDevise' => $reqCompte->findOneBy(['intitule'=>'CV devise Caisse 3']),
                'compteIntercaisse' => $compteIntercaisse,
                'compteAttenteCompense' => $compteCompense,
                'journeeCaisse' => null,
                'typeCaisse'=>Caisses::GUICHET
            ]
        ,['libelle' => 'Caisse menu depense',
                'code' => 'CMD',
                'compteOperation' => $reqCompte->findOneBy(['intitule'=>'Caisse menu depenses']),
                'compteCvDevise' => $reqCompte->findOneBy(['intitule'=>null]),
                'compteIntercaisse' => $compteIntercaisse,
                'compteAttenteCompense' => $compteCompense,
                'journeeCaisse' => null,
                'typeCaisse'=>Caisses::MENUDEPENSE
            ]
        ,['libelle' => 'Caisse Appro',
                'code' => 'APPRO',
                'compteOperation' => $reqCompte->findOneBy(['intitule'=>'Coris Bank Compense']),
                'compteCvDevise' => $reqCompte->findOneBy(['intitule'=>null]),
                'compteIntercaisse' => $compteIntercaisse,
                'compteAttenteCompense' => $compteCompense,
                'journeeCaisse' => null,
                'typeCaisse'=>Caisses::COMPENSE
            ]
            /*,['libelle' => 'PISSY KD03', 'code' => 'KD03','compteOperation' => $compteOperationCaisse3, 'compteCvDevise' => $compteCvDevise3, 'compteIntercaisse' => $compteIntercaisse, 'journeeCaisse' => $idJourneeCaisseO]
            ,['libelle' => 'Caisse menu depense', 'code' => 'CMD', 'compteOperation' => $compteOperationCaisseCMD, 'compteCvDevise' => null, 'compteIntercaisse' => $compteIntercaisse, 'journeeCaisse' => null]
            ,['libelle' => 'DAPOYA KD02', 'code' => 'KD02', 'compteOperation' => $compteOperationCaisse2, 'compteCvDevise' => $compteCvDevise2, 'compteIntercaisse' => $compteIntercaisse, 'journeeCaisse' => null]
            */
        );

        foreach ($lists as $list) {
            $enr = new Caisses($manager);
            $enr->setLibelle($list['libelle'])->setCode($list['code'])->setCompteOperation($list['compteOperation'])->setCompteCvDevise($list['compteCvDevise'])->setCompteIntercaisse($list['compteIntercaisse'])->setCompteAttenteCompense($list['compteAttenteCompense'])->setTypeCaisse($list['typeCaisse']);
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