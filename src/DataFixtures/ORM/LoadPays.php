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
            array('libelle'=>'Burkina Faso', 'zone'=>$zoneUEMOA, 'dansRegion'=>true, 'ordre'=>2),
            array('libelle'=>'Mali', 'zone'=>$zoneUEMOA, 'dansRegion'=>true, 'ordre'=>5),
            array('libelle'=>"Cote d'Ivoire", 'zone'=>$zoneUEMOA, 'dansRegion'=>true, 'ordre'=>3),
            array('libelle'=>'Niger', 'zone'=>$zoneUEMOA, 'dansRegion'=>true, 'ordre'=>6),
            array('libelle'=>'Guinee Bissau', 'zone'=>$zoneUEMOA, 'dansRegion'=>true, 'ordre'=>4),
            array('libelle'=>'Benin', 'zone'=>$zoneUEMOA, 'dansRegion'=>true, 'ordre'=>1),
            array('libelle'=>'Togo', 'zone'=>$zoneUEMOA, 'dansRegion'=>true, 'ordre'=>8),
            array('libelle'=>'Sénégal', 'zone'=>$zoneUEMOA, 'dansRegion'=>true, 'ordre'=>7),
            //autres CEDEAO
            array('libelle'=>'Ghana', 'zone'=>$zoneCEDEAO, 'dansRegion'=>false, 'ordre'=>1),
            array('libelle'=>'Guinée Conakry', 'zone'=>$zoneCEDEAO, 'dansRegion'=>false, 'ordre'=>2),
            array('libelle'=>'Nigeria', 'zone'=>$zoneCEDEAO, 'dansRegion'=>false, 'ordre'=>3),
            array('libelle'=>'Liberia', 'zone'=>$zoneCEDEAO, 'dansRegion'=>false, 'ordre'=>4),
            array('libelle'=>'Gambie', 'zone'=>$zoneCEDEAO, 'dansRegion'=>false, 'ordre'=>5),
            array('libelle'=>'Serra Leone', 'zone'=>$zoneCEDEAO, 'dansRegion'=>false, 'ordre'=>6),
            array('libelle'=>'Cap Vert', 'zone'=>$zoneCEDEAO, 'dansRegion'=>false, 'ordre'=>7),
            ////////CEMAC/////
            array('libelle'=>'Cameroun', 'zone'=>$zoneCEMAC, 'dansRegion'=>false, 'ordre'=>1),
            array('libelle'=>'République Centrafricaine', 'zone'=>$zoneCEMAC, 'dansRegion'=>false, 'ordre'=>2),
            array('libelle'=>'Congo - RDC', 'zone'=>$zoneCEMAC, 'dansRegion'=>false, 'ordre'=>3),
            array('libelle'=>'Congo Brazza', 'zone'=>$zoneCEMAC, 'dansRegion'=>false, 'ordre'=>4),
            array('libelle'=>'Gabon', 'zone'=>$zoneCEMAC, 'dansRegion'=>false, 'ordre'=>5),
            array('libelle'=>'Tchad', 'zone'=>$zoneCEMAC, 'dansRegion'=>false, 'ordre'=>6),
            array('libelle'=>'Guinée Equatoriale', 'zone'=>$zoneCEMAC, 'dansRegion'=>false, 'ordre'=>7),
            /////////////MAGHREB/////////////
            array('libelle'=>'Tunisie', 'zone'=>$zoneMAGHREB, 'dansRegion'=>false, 'ordre'=>1),
            array('libelle'=>'Algerie', 'zone'=>$zoneMAGHREB, 'dansRegion'=>false, 'ordre'=>2),
            array('libelle'=>'Maroc', 'zone'=>$zoneMAGHREB, 'dansRegion'=>false, 'ordre'=>3),
            ////////////Afrique du Sud//////////
            array('libelle'=>'Afrique du Sud', 'zone'=>$zoneAFRIQUESUD, 'dansRegion'=>false, 'ordre'=>1),
            ////////USA///////////////
            array('libelle'=>'USA', 'zone'=>$zoneUSA, 'dansRegion'=>false, 'ordre'=>1),
            ///////////EUROPE/////////////
            array('libelle'=>'France', 'zone'=>$zoneEUROPE, 'dansRegion'=>false, 'ordre'=>1),
            array('libelle'=>'Espagne', 'zone'=>$zoneEUROPE, 'dansRegion'=>false, 'ordre'=>2),
            array('libelle'=>'Italie', 'zone'=>$zoneEUROPE, 'dansRegion'=>false, 'ordre'=>3),
            array('libelle'=>'Portugal', 'zone'=>$zoneEUROPE, 'dansRegion'=>false, 'ordre'=>4),
            array('libelle'=>'Belgique', 'zone'=>$zoneEUROPE, 'dansRegion'=>false, 'ordre'=>5),
            array('libelle'=>'Grèce', 'zone'=>$zoneEUROPE, 'dansRegion'=>false, 'ordre'=>6),
            array('libelle'=>'Royaume-Unie', 'zone'=>$zoneEUROPE, 'dansRegion'=>false, 'ordre'=>7),
            array('libelle'=>'Luxembourg', 'zone'=>$zoneEUROPE, 'dansRegion'=>false, 'ordre'=>8),
            array('libelle'=>'Allemagne', 'zone'=>$zoneEUROPE, 'dansRegion'=>false, 'ordre'=>9),
            array('libelle'=>'Danemark', 'zone'=>$zoneEUROPE, 'dansRegion'=>false, 'ordre'=>10),
            ////////INDE///////////////
            array('libelle'=>'Inde', 'zone'=>$zoneINDE, 'dansRegion'=>false, 'ordre'=>1),
            ////////CHINE///////////////
            array('libelle'=>'Chine', 'zone'=>$zoneCHINE, 'dansRegion'=>false, 'ordre'=>1),
            ////////BRESIL///////////////
            array('libelle'=>'Bresil', 'zone'=>$zoneBRESIL, 'dansRegion'=>false, 'ordre'=>1),
            ////////AUTRES///////////////
            array('libelle'=>'XAutres', 'zone'=>$zoneAUTRES, 'dansRegion'=>false, 'ordre'=>1),


        );

        foreach ($lists as $list) {
            $objet=new Pays();
            $objet->setLibelle($list['libelle'])->setZone($list['zone'])->setDansRegion($list['dansRegion'])->setOrdre($list['ordre']);
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