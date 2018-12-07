<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 03/07/2018
 * Time: 18:53
 */

namespace App\DataFixtures\ORM;

use App\Entity\Billets;
use App\Entity\Pays;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
//use Doctrine\Common\DataFixtures\DependentFixtureInterface;



class LoadPays extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $lists=array (
            array('libelle'=>'Burkina Faso', 'zone'=>'UEMOA'),
            array('libelle'=>'Mali', 'zone'=>'UEMOA'),
            array('libelle'=>"Cote d'Ivoire", 'zone'=>'UEMOA'),
            array('libelle'=>'Niger', 'zone'=>'UEMOA'),
            array('libelle'=>'Guinee Conakry', 'zone'=>'UEMOA'),
            array('libelle'=>'Benin', 'zone'=>'UEMOA'),
            array('libelle'=>'Togo', 'zone'=>'UEMOA'),
            ////////CEMAC/////
            array('libelle'=>'Cameroun', 'zone'=>'CEMAC'),
            array('libelle'=>'République Centrafricaine', 'zone'=>'CEMAC'),
            array('libelle'=>'République du Congo', 'zone'=>'CEMAC'),
            array('libelle'=>'Congo', 'zone'=>'CEMAC'),
            array('libelle'=>'Tchad', 'zone'=>'CEMAC'),
            array('libelle'=>'Guinée Equatoriale', 'zone'=>'CEMAC'),
            /////////////MAGHREB/////////////
            array('libelle'=>'Tunisie', 'zone'=>'MAGHREB'),
            array('libelle'=>'Algerie', 'zone'=>'MAGHREB'),
            array('libelle'=>'Maroc', 'zone'=>'MAGHREB'),
            ////////////Afrique du Sud//////////
            array('libelle'=>'Afrique du Sud', 'zone'=>'Afrique du Sud'),
            ////////USA///////////////
            array('libelle'=>'USA', 'zone'=>'USA'),
            ///////////EUROPE/////////////
            array('libelle'=>'France', 'zone'=>'EUROPE'),
            array('libelle'=>'Espagne', 'zone'=>'EUROPE'),
            array('libelle'=>'Italie', 'zone'=>'EUROPE'),
            array('libelle'=>'Portugal', 'zone'=>'EUROPE'),
            array('libelle'=>'Belgique', 'zone'=>'EUROPE'),
            array('libelle'=>'Grèce', 'zone'=>'EUROPE'),
            array('libelle'=>'Royaume-Unie', 'zone'=>'EUROPE'),
            array('libelle'=>'Luxembourg', 'zone'=>'EUROPE'),
            array('libelle'=>'Allemagne', 'zone'=>'EUROPE'),
            array('libelle'=>'Danemark', 'zone'=>'EUROPE'),
            ////////USA///////////////
            array('libelle'=>'INDE', 'zone'=>'INDE'),
            ////////USA///////////////
            array('libelle'=>'CHINE', 'zone'=>'CHINE'),
            ////////USA///////////////
            array('libelle'=>'BRESIL', 'zone'=>'BRESIL'),
            ////////USA///////////////
            array('libelle'=>'AUTRES', 'zone'=>'AUTRES'),


        );

        foreach ($lists as $list) {
            $objet=new Pays();
            $objet->setLibelle($list['libelle'])->setZone($list['zone']);
            $manager->persist($objet);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }
}