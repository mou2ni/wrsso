<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 05/07/2018
 * Time: 05:40
 */

namespace App\DataFixtures\ORM;

use App\Entity\Comptes;
use App\Entity\Utilisateurs;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadUtilisateurs extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $compteEcartCaissier1=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Ecarts caissier 1']);
        $compteEcartCaissier2=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Ecarts caissier 2']);
        //$compteCompenseCaissier1=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Compense caissier 1']);
        //$compteCompenseCaissier2=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Compense caissier 2']);
        $lists = array(['login' => 'houedraogo', 'mdp' => 'MD5MDP', 'nom' => 'OUEDRAOGO', 'prenom' => 'Sayouba', 'estCaissier' => true, 'statut' => 'a', 'compteEcartCaisse'=>$compteEcartCaissier1]
        , ['login' => 'asanou', 'mdp' => 'MD5MDPASANOU', 'nom' => 'SANOU', 'prenom' => 'Alfred', 'estCaissier' => true, 'statut' => 'a', 'compteEcartCaisse'=>$compteEcartCaissier2]
        );

        foreach ($lists as $list) {
            $enr = new Utilisateurs();
            $enr->setLogin($list['login'])->setMdp($list['mdp'])->setNom($list['nom'])->setPrenom($list['prenom'])->setEstCaissier($list['estCaissier'])->setStatus($list['statut'])->setCompteEcartCaisse($list['compteEcartCaisse'])
            ;
            $manager->persist($enr);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(LoadComptes::class);
    }

}