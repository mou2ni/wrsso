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
        $listClients=array (array('nom'=>'OUEDRAOGO','prenom'=>'Hamado', 'adresse'=>'02 BP 6206 Ouaga 02'),
                            array('nom'=>'OUEDRAOGO','prenom'=>'Moumouni', 'adresse'=>'TANGUIN/OUAGA'),
                            array('nom'=>'Comptes','prenom'=>'Interne', 'adresse'=>'YESBO')
        );

        foreach ($listClients as $listClient) {
            $client=new Clients();
            $client->setNom($listClient['nom'])->setPrenom($listClient['prenom'])->setAdresse($listClient['adresse']);
            $manager->persist($client);
        }

        $manager->flush();
    }

}