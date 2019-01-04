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
            array('nom'=>'Comptes','prenom'=>'Interne', 'adresse'=>'YESBO'),
            array('nom'=>'OUEDRAOGO','prenom'=>'Hamado', 'adresse'=>'02 BP 6206 Ouaga 02'),
            array('nom'=>'OUEDRAOGO','prenom'=>'Moumouni', 'adresse'=>'TANGUIN/OUAGA'),
            array('nom'=>'GANOU','prenom'=>'Celine', 'adresse'=>'GOUNGHIN/OUAGA'),
            array('nom'=>'OUEDRAOGO','prenom'=>'Lassina', 'adresse'=>'TANGUIN/OUAGA'),
            array('nom'=>'OUEDRAOGO','prenom'=>'Bassirou', 'adresse'=>'TANGUIN/OUAGA'),
            array('nom'=>'OUEDRAOGO','prenom'=>'Hamidou', 'adresse'=>'TANGUIN/OUAGA'),
            array('nom'=>'OUEDRAOGO','prenom'=>'Sidiki', 'adresse'=>'DORI'),
            array('nom'=>'TOUBRI','prenom'=>'Ghislaine', 'adresse'=>'DASSASGO/OUAGA'),
            array('nom'=>'SAKANDE','prenom'=>'Aina', 'adresse'=>'DASSASGO/OUAGA'),
            array('nom'=>'BAYOULOU','prenom'=>'Ami', 'adresse'=>'DASSASGO/OUAGA'),
            array('nom'=>'SORE','prenom'=>'Fatim', 'adresse'=>'TANGUIN/OUAGA'),
            array('nom'=>'TAMBOURA','prenom'=>'Djanna', 'adresse'=>'DASSASGO/OUAGA'),
            array('nom'=>'COULIBALY','prenom'=>'Djeneba', 'adresse'=>'SANKARYARE/OUAGA'),
            array('nom'=>'ZONOU','prenom'=>'Amidou', 'adresse'=>'DASSASGO/OUAGA'),
            array('nom'=>'YESBO','prenom'=>'Tontine', 'adresse'=>'DAPOYA/OUAGA'),
            array('nom'=>'BANK','prenom'=>'Coris', 'adresse'=>'OUAGA'),
            array('nom'=>'OUEDRAOGO / KANYALA','prenom'=>'Lucie', 'adresse'=>'PISSY/OUAGA'),
        );

        foreach ($listClients as $listClient) {
            $client=new Clients();
            $client->setNom($listClient['nom'])->setPrenom($listClient['prenom'])->setAdresse($listClient['adresse']);
            $manager->persist($client);
        }

        $manager->flush();
    }

}