<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 03/07/2018
 * Time: 18:53
 */

namespace App\DataFixtures\ORM;

use App\Entity\Clients;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadClients extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $listClients=array (
            array('nom'=>'Comptes','prenom'=>'Interne', 'adresse'=>'YESBO','typeTier'=>Clients::TYP_INTERNE),
            array('nom'=>'OUEDRAOGO','prenom'=>'Hamado', 'adresse'=>'02 BP 6206 Ouaga 02','typeTier'=>Clients::TYP_PERSONNEL),
            array('nom'=>'OUEDRAOGO','prenom'=>'Moumouni', 'adresse'=>'TANGUIN/OUAGA','typeTier'=>Clients::TYP_PERSONNEL),
            array('nom'=>'GANOU','prenom'=>'Celine', 'adresse'=>'GOUNGHIN/OUAGA','typeTier'=>Clients::TYP_PERSONNEL),
            array('nom'=>'OUEDRAOGO','prenom'=>'Lassina', 'adresse'=>'TANGUIN/OUAGA','typeTier'=>Clients::TYP_PERSONNEL),
            array('nom'=>'OUEDRAOGO','prenom'=>'Bassirou', 'adresse'=>'TANGUIN/OUAGA','typeTier'=>Clients::TYP_DIVERS),
            array('nom'=>'OUEDRAOGO','prenom'=>'Hamidou', 'adresse'=>'TANGUIN/OUAGA','typeTier'=>Clients::TYP_PERSONNEL),
            array('nom'=>'OUEDRAOGO','prenom'=>'Sidiki', 'adresse'=>'DORI','typeTier'=>Clients::TYP_DIVERS),
            array('nom'=>'TOUBRI','prenom'=>'Ghislaine', 'adresse'=>'DASSASGO/OUAGA','typeTier'=>Clients::TYP_PERSONNEL),
            array('nom'=>'SAKANDE','prenom'=>'Aina', 'adresse'=>'DASSASGO/OUAGA','typeTier'=>Clients::TYP_PERSONNEL),
            array('nom'=>'BAYOULOU','prenom'=>'Ami', 'adresse'=>'DASSASGO/OUAGA','typeTier'=>Clients::TYP_PERSONNEL),
            array('nom'=>'SORE','prenom'=>'Fatim', 'adresse'=>'TANGUIN/OUAGA','typeTier'=>Clients::TYP_PERSONNEL),
            array('nom'=>'TAMBOURA','prenom'=>'Djanna', 'adresse'=>'DASSASGO/OUAGA','typeTier'=>Clients::TYP_PERSONNEL),
            array('nom'=>'COULIBALY','prenom'=>'Djeneba', 'adresse'=>'SANKARYARE/OUAGA','typeTier'=>Clients::TYP_PERSONNEL),
            array('nom'=>'ZONOU','prenom'=>'Amidou', 'adresse'=>'DASSASGO/OUAGA','typeTier'=>Clients::TYP_PERSONNEL),
            array('nom'=>'YESBO','prenom'=>'Tontine', 'adresse'=>'DAPOYA/OUAGA','typeTier'=>Clients::TYP_INTERNE),
            array('nom'=>'BANK','prenom'=>'Coris', 'adresse'=>'OUAGA','typeTier'=>Clients::TYP_INTERNE),
            array('nom'=>'OUEDRAOGO / KANYALA','prenom'=>'Lucie', 'adresse'=>'PISSY/OUAGA','typeTier'=>Clients::TYP_DIVERS),
        );

        foreach ($listClients as $listClient) {
            $client=new Clients();
            $client->setNom($listClient['nom'])->setPrenom($listClient['prenom'])->setAdresse($listClient['adresse'])
            ->setTypeTier($listClient['typeTier']);
            $manager->persist($client);
        }

        $manager->flush();
    }

}