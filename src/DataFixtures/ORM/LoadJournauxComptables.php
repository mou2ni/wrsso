<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 04/07/2018
 * Time: 14:19
 */

namespace App\DataFixtures\ORM;

use App\Entity\JournauxComptables;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Comptes;


class LoadJournauxComptables extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $compteKD00=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Opérations Caisse 0']);
        $compteKD01=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Opérations Caisse 1']);
        $compteKD02=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Opérations Caisse 2']);
        $compteKD03=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Opérations Caisse 3']);
        $compteCMD=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Caisse menu depenses']);
        $compteAPPRO=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Coris Bank Compense']);

        $lists=array (
            ///COMPTES ORDINAIRES
            array('libelle'=>'Journal caisse KD00','code'=>'KD00', 'compteContrePartie'=>$compteKD00, 'typeJournal'=>JournauxComptables::TYP_TRESORERIE)
            ,array('libelle'=>'Journal caisse KD01','code'=>'KO01', 'compteContrePartie'=>$compteKD01, 'typeJournal'=>JournauxComptables::TYP_TRESORERIE)
            ,array('libelle'=>'Journal caisse KD02','code'=>'KD02', 'compteContrePartie'=>$compteKD02, 'typeJournal'=>JournauxComptables::TYP_TRESORERIE)
            ,array('libelle'=>'Journal caisse KD03','code'=>'KD03', 'compteContrePartie'=>$compteKD03, 'typeJournal'=>JournauxComptables::TYP_TRESORERIE)
            ,array('libelle'=>'Journal caisse CMD','code'=>'CMD', 'compteContrePartie'=>$compteCMD, 'typeJournal'=>JournauxComptables::TYP_TRESORERIE)
            ,array('libelle'=>'Journal caisse APPRO','code'=>'APPRO', 'compteContrePartie'=>$compteAPPRO, 'typeJournal'=>JournauxComptables::TYP_TRESORERIE)
            ,array('libelle'=>'Journal Vente','code'=>'VTE', 'compteContrePartie'=>null, 'typeJournal'=>JournauxComptables::TYP_VENTE)
            ,array('libelle'=>'Journal Achat','code'=>'ACHT', 'compteContrePartie'=>null, 'typeJournal'=>JournauxComptables::TYP_ACHAT)            
            ,array('libelle'=>'Journal Paye','code'=>'PAYE', 'compteContrePartie'=>null, 'typeJournal'=>JournauxComptables::TYP_PAYE)

        );

        foreach ($lists as $list){
            $enr=new JournauxComptables();
            $enr->setCode($list['code'])
                ->setCompteContrePartie($list['compteContrePartie'])
                ->setLibelle($list['libelle'])
                ->setTypeJournal($list['typeJournal']);
            $manager->persist($enr);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            LoadComptes::class,
        );
    }

}