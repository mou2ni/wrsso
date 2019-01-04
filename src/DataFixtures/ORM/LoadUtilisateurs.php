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
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class LoadUtilisateurs extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $reqCompte = $manager->getRepository(Comptes::class);
        //$encoder = $this->container->get('security.password_encoder');
        $compteEcartCaissier1=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Ecarts caissier 1']);
        $compteEcartCaissier2=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Ecarts caissier 2']);


        //$mdp = $this->container->get('security.password_encoder')->encodePassword('login', 'login');
        //$mdp = $this->container->get('security.password_encoder')->encodePassword('login1');
        //$2y$12$kB6SgGAC.G/Hcdhv0iaYd.0dI4RJNpHrVR5gTZVE9qfQNVjprdspi login
        //$2y$12$pKORCggAl.2/MlEmkTspMuydovYzM5yhMTBlYVIbJd7BFHutWE4u. login1

        $lists = array(
            ['login' => 'lassina', 'mdp' => 'lassina', 'nom' => 'OUEDRAOGO', 'prenom' => 'Lassina',
                'estCaissier' => true, 'statut' => 'a',
                'compteEcartCaisse'=>$reqCompte->findOneBy(['intitule'=>'Ecarts caissier Lassina']), 'role'=>'ROLE_USER']
        , ['login' => 'ghislaine', 'mdp' => 'ghislaine', 'nom' => 'TOUBRI', 'prenom' => 'Ghislaine',
                'estCaissier' => true, 'statut' => 'a',
                'compteEcartCaisse'=>$reqCompte->findOneBy(['intitule'=>'Ecarts caissier Ghislaine']), 'role'=>'ROLE_USER']
        , ['login' => 'fatim', 'mdp' => 'fatim', 'nom' => 'SORE', 'prenom' => 'Fatim',
                'estCaissier' => true, 'statut' => 'a',
                'compteEcartCaisse'=>$reqCompte->findOneBy(['intitule'=>'Ecarts caissier Fatim']), 'role'=>'ROLE_USER']
        , ['login' => 'aina', 'mdp' => 'aina', 'nom' => 'SAKANDE', 'prenom' => 'Aina',
                'estCaissier' => true, 'statut' => 'a',
                'compteEcartCaisse'=>$reqCompte->findOneBy(['intitule'=>'Ecarts caissier Aina']), 'role'=>'ROLE_USER']
        , ['login' => 'ganou', 'mdp' => 'ganou', 'nom' => 'GANOU', 'prenom' => 'Celine',
                'estCaissier' => true, 'statut' => 'a',
                'compteEcartCaisse'=>$reqCompte->findOneBy(['intitule'=>'Ecarts caissier Celine']), 'role'=>'ROLE_USER']
        , ['login' => 'bayoulou', 'mdp' => 'bayoulou', 'nom' => 'BAYOULOU', 'prenom' => 'Ami',
                'estCaissier' => true, 'statut' => 'a',
                'compteEcartCaisse'=>$reqCompte->findOneBy(['intitule'=>'Ecarts caissier Ami']), 'role'=>'ROLE_USER']
        , ['login' => 'tamboura', 'mdp' => 'tamboura', 'nom' => 'TAMBOURA', 'prenom' => 'Djanna',
                'estCaissier' => true, 'statut' => 'a',
                'compteEcartCaisse'=>$reqCompte->findOneBy(['intitule'=>'Ecarts caissier Djanna']), 'role'=>'ROLE_USER']
        , ['login' => 'admin', 'mdp' => 'admin', 'nom' => 'YESBO', 'prenom' => 'Admin',
                'estCaissier' => false, 'statut' => 'a', 'compteEcartCaisse'=>null, 'role'=>'ROLE_USER']
        );

        foreach ($lists as $list) {
            $enr = new Utilisateurs();
            $enr->setLogin($list['login'])->setMdp($list['mdp'])->setNom($list['nom'])->setPrenom($list['prenom'])->setEstCaissier($list['estCaissier'])->setStatus($list['statut'])->setCompteEcartCaisse($list['compteEcartCaisse'])->setRole($list['role'])
            ;
            $enr->setMdp($this->encoder->encodePassword($enr,$list['mdp']));
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