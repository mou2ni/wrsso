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
use App\Entity\Zones;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;



class LoadPays extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $zoneUEMOA=$manager->getRepository(Zones::class)->findOneBy(['code'=>'UEMOA']);
        $zoneCEDEAO=$manager->getRepository(Zones::class)->findOneBy(['code'=>'CEDEAO']);
        $zoneCEMAC=$manager->getRepository(Zones::class)->findOneBy(['code'=>'CEMAC']);
        $zoneMAGHREB=$manager->getRepository(Zones::class)->findOneBy(['code'=>'MAGHREB']);
        $zoneAFRIQUESUD=$manager->getRepository(Zones::class)->findOneBy(['code'=>'AFRIQUESUD']);
        $zoneUSA=$manager->getRepository(Zones::class)->findOneBy(['code'=>'USA']);
        $zoneEUROPE=$manager->getRepository(Zones::class)->findOneBy(['code'=>'EUROPE']);
        $zoneINDE=$manager->getRepository(Zones::class)->findOneBy(['code'=>'INDE']);
        $zoneCHINE=$manager->getRepository(Zones::class)->findOneBy(['code'=>'CHINE']);
        $zoneBRESIL=$manager->getRepository(Zones::class)->findOneBy(['code'=>'BRESIL']);
        $zoneAUTRES=$manager->getRepository(Zones::class)->findOneBy(['code'=>'AUTRES']);
        $lists=array (
            array('libelle'=>'Burkina Faso', 'zone'=>$zoneUEMOA, 'dansRegion'=>true),
            array('libelle'=>'Mali', 'zone'=>$zoneUEMOA, 'dansRegion'=>true),
            array('libelle'=>"Cote d'Ivoire", 'zone'=>$zoneUEMOA, 'dansRegion'=>true),
            array('libelle'=>'Niger', 'zone'=>$zoneUEMOA, 'dansRegion'=>true),
            array('libelle'=>'Guinee Bissau', 'zone'=>$zoneUEMOA, 'dansRegion'=>true),
            array('libelle'=>'Benin', 'zone'=>$zoneUEMOA, 'dansRegion'=>true),
            array('libelle'=>'Togo', 'zone'=>$zoneUEMOA, 'dansRegion'=>true),
            array('libelle'=>'Sénégal', 'zone'=>$zoneUEMOA, 'dansRegion'=>true),
            //autres CEDEAO
            array('libelle'=>'Ghana', 'zone'=>$zoneCEDEAO, 'dansRegion'=>false),
            array('libelle'=>'Guinée Conakry', 'zone'=>$zoneCEDEAO, 'dansRegion'=>false),
            array('libelle'=>'Nigeria', 'zone'=>$zoneCEDEAO, 'dansRegion'=>false),
            array('libelle'=>'Liberia', 'zone'=>$zoneCEDEAO, 'dansRegion'=>false),
            array('libelle'=>'Gambie', 'zone'=>$zoneCEDEAO, 'dansRegion'=>false),
            array('libelle'=>'Serra Leone', 'zone'=>$zoneCEDEAO, 'dansRegion'=>false),
            array('libelle'=>'Cap Vert', 'zone'=>$zoneCEDEAO, 'dansRegion'=>false),
            ////////CEMAC/////
            array('libelle'=>'Cameroun', 'zone'=>$zoneCEMAC, 'dansRegion'=>false),
            array('libelle'=>'République Centrafricaine', 'zone'=>$zoneCEMAC, 'dansRegion'=>false),
            array('libelle'=>'Congo - RDC', 'zone'=>$zoneCEMAC, 'dansRegion'=>false),
            array('libelle'=>'Congo Brazza', 'zone'=>$zoneCEMAC, 'dansRegion'=>false),
            array('libelle'=>'Gabon', 'zone'=>$zoneCEMAC, 'dansRegion'=>false),
            array('libelle'=>'Tchad', 'zone'=>$zoneCEMAC, 'dansRegion'=>false),
            array('libelle'=>'Guinée Equatoriale', 'zone'=>$zoneCEMAC, 'dansRegion'=>false),
            /////////////MAGHREB/////////////
            array('libelle'=>'Tunisie', 'zone'=>$zoneMAGHREB, 'dansRegion'=>false),
            array('libelle'=>'Algerie', 'zone'=>$zoneMAGHREB, 'dansRegion'=>false),
            array('libelle'=>'Maroc', 'zone'=>$zoneMAGHREB, 'dansRegion'=>false),
            ////////////Afrique du Sud//////////
            array('libelle'=>'Afrique du Sud', 'zone'=>$zoneAFRIQUESUD, 'dansRegion'=>false),
            ////////USA///////////////
            array('libelle'=>'USA', 'zone'=>$zoneUSA, 'dansRegion'=>false),
            ///////////EUROPE/////////////
            array('libelle'=>'France', 'zone'=>$zoneEUROPE, 'dansRegion'=>false),
            array('libelle'=>'Espagne', 'zone'=>$zoneEUROPE, 'dansRegion'=>false),
            array('libelle'=>'Italie', 'zone'=>$zoneEUROPE, 'dansRegion'=>false),
            array('libelle'=>'Portugal', 'zone'=>$zoneEUROPE, 'dansRegion'=>false),
            array('libelle'=>'Belgique', 'zone'=>$zoneEUROPE, 'dansRegion'=>false),
            array('libelle'=>'Grèce', 'zone'=>$zoneEUROPE, 'dansRegion'=>false),
            array('libelle'=>'Royaume-Unie', 'zone'=>$zoneEUROPE, 'dansRegion'=>false),
            array('libelle'=>'Luxembourg', 'zone'=>$zoneEUROPE, 'dansRegion'=>false),
            array('libelle'=>'Allemagne', 'zone'=>$zoneEUROPE, 'dansRegion'=>false),
            array('libelle'=>'Danemark', 'zone'=>$zoneEUROPE, 'dansRegion'=>false),
            ////////USA///////////////
            array('libelle'=>'Inde', 'zone'=>$zoneINDE, 'dansRegion'=>false),
            ////////USA///////////////
            array('libelle'=>'Chine', 'zone'=>$zoneCHINE, 'dansRegion'=>false),
            ////////USA///////////////
            array('libelle'=>'Bresil', 'zone'=>$zoneBRESIL, 'dansRegion'=>false),
            ////////USA///////////////
            array('libelle'=>'XAutres', 'zone'=>$zoneAUTRES, 'dansRegion'=>false),


        );

        foreach ($lists as $list) {
            $objet=new Pays();
            $objet->setLibelle($list['libelle'])->setZone($list['zone'])->setDansRegion($list['dansRegion']);
            $manager->persist($objet);
        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            LoadZones::class,
        );
    }
}