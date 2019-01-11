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
            array('libelle'=>'Burkina Faso', 'zone'=>'UEMOA', 'dansRegion'=>true),
            array('libelle'=>'Mali', 'zone'=>'UEMOA', 'dansRegion'=>true),
            array('libelle'=>"Cote d'Ivoire", 'zone'=>'UEMOA', 'dansRegion'=>true),
            array('libelle'=>'Niger', 'zone'=>'UEMOA', 'dansRegion'=>true),
            array('libelle'=>'Guinee Bissau', 'zone'=>'UEMOA', 'dansRegion'=>true),
            array('libelle'=>'Benin', 'zone'=>'UEMOA', 'dansRegion'=>true),
            array('libelle'=>'Togo', 'zone'=>'UEMOA', 'dansRegion'=>true),
            array('libelle'=>'Sénégal', 'zone'=>'UEMOA', 'dansRegion'=>true),
            ////////CEMAC/////
            array('libelle'=>'Cameroun', 'zone'=>'CEMAC', 'dansRegion'=>false),
            array('libelle'=>'République Centrafricaine', 'zone'=>'CEMAC', 'dansRegion'=>false),
            array('libelle'=>'République du Congo', 'zone'=>'CEMAC', 'dansRegion'=>false),
            array('libelle'=>'Congo', 'zone'=>'CEMAC', 'dansRegion'=>false),
            array('libelle'=>'Tchad', 'zone'=>'CEMAC', 'dansRegion'=>false),
            array('libelle'=>'Guinée Equatoriale', 'zone'=>'CEMAC', 'dansRegion'=>false),
            /////////////MAGHREB/////////////
            array('libelle'=>'Tunisie', 'zone'=>'MAGHREB', 'dansRegion'=>false),
            array('libelle'=>'Algerie', 'zone'=>'MAGHREB', 'dansRegion'=>false),
            array('libelle'=>'Maroc', 'zone'=>'MAGHREB', 'dansRegion'=>false),
            ////////////Afrique du Sud//////////
            array('libelle'=>'Afrique du Sud', 'zone'=>'Afrique du Sud', 'dansRegion'=>false),
            ////////USA///////////////
            array('libelle'=>'USA', 'zone'=>'USA', 'dansRegion'=>false),
            ///////////EUROPE/////////////
            array('libelle'=>'France', 'zone'=>'EUROPE', 'dansRegion'=>false),
            array('libelle'=>'Espagne', 'zone'=>'EUROPE', 'dansRegion'=>false),
            array('libelle'=>'Italie', 'zone'=>'EUROPE', 'dansRegion'=>false),
            array('libelle'=>'Portugal', 'zone'=>'EUROPE', 'dansRegion'=>false),
            array('libelle'=>'Belgique', 'zone'=>'EUROPE', 'dansRegion'=>false),
            array('libelle'=>'Grèce', 'zone'=>'EUROPE', 'dansRegion'=>false),
            array('libelle'=>'Royaume-Unie', 'zone'=>'EUROPE', 'dansRegion'=>false),
            array('libelle'=>'Luxembourg', 'zone'=>'EUROPE', 'dansRegion'=>false),
            array('libelle'=>'Allemagne', 'zone'=>'EUROPE', 'dansRegion'=>false),
            array('libelle'=>'Danemark', 'zone'=>'EUROPE', 'dansRegion'=>false),
            ////////USA///////////////
            array('libelle'=>'INDE', 'zone'=>'INDE', 'dansRegion'=>false),
            ////////USA///////////////
            array('libelle'=>'CHINE', 'zone'=>'CHINE', 'dansRegion'=>false),
            ////////USA///////////////
            array('libelle'=>'BRESIL', 'zone'=>'BRESIL', 'dansRegion'=>false),
            ////////USA///////////////
            array('libelle'=>'AUTRES', 'zone'=>'AUTRES', 'dansRegion'=>false),


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