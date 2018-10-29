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

        //$mdp = $this->container->get('security.password_encoder')->encodePassword('login');
        //$mdp = $this->container->get('security.password_encoder')->encodePassword('login1');

        $lists = array(['login' => 'login', 'mdp' => '$2y$12$kB6SgGAC.G/Hcdhv0iaYd.0dI4RJNpHrVR5gTZVE9qfQNVjprdspi', 'nom' => 'OUEDRAOGO', 'prenom' => 'Sayouba', 'estCaissier' => true, 'statut' => 'a', 'compteEcartCaisse'=>$compteEcartCaissier1, 'role'=>'ROLE_USER']
        , ['login' => 'login1', 'mdp' => '$2y$12$pKORCggAl.2/MlEmkTspMuydovYzM5yhMTBlYVIbJd7BFHutWE4u.', 'nom' => 'SANOU', 'prenom' => 'Alfred', 'estCaissier' => true, 'statut' => 'a', 'compteEcartCaisse'=>$compteEcartCaissier2, 'role'=>'ROLE_USER']
        );

        foreach ($lists as $list) {
            $enr = new Utilisateurs();
            $enr->setLogin($list['login'])->setMdp($list['mdp'])->setNom($list['nom'])->setPrenom($list['prenom'])->setEstCaissier($list['estCaissier'])->setStatus($list['statut'])->setCompteEcartCaisse($list['compteEcartCaisse'])->setRole($list['role'])
            ;
            $manager->persist($enr);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            LoadComptes::class
        );
    }

}