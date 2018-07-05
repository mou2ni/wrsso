<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 05/07/2018
 * Time: 05:40
 */

namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Utilisateurs;

class LoadUtilisateurs extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $lists = array(['login' => 'houedraogo', 'mdp' => 'MD5MDP', 'nom' => 'OUEDRAOGO', 'prenom' => 'Sayouba', 'estCaissier' => true, 'statut' => 'a']
        , ['login' => 'asanou', 'mdp' => 'MD5MDPASANOU', 'nom' => 'SANOU', 'prenom' => 'Alfred', 'estCaissier' => true, 'statut' => 'a']
        );

        foreach ($lists as $list) {
            $enr = new Utilisateurs();
            $enr->setLogin($list['login'])->setMdp($list['mdp'])->setNom($list['nom'])->setPrenom($list['prenom'])->setEstCaissier($list['estCaissier'])->setStatus($list['statut']);
            $manager->persist($enr);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 40;
    }
}