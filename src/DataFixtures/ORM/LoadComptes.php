<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 04/07/2018
 * Time: 05:50
 */

namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Comptes;
use App\Entity\Clients;

class LoadComptes extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $cltHamado=$manager->getRepository(Clients::class)->findOneBy(['prenom'=>'Hamado']);
        $cltMoumouni=$manager->getRepository(Clients::class)->findOneBy(['prenom'=>'Moumouni']);
        $cltInterne=$manager->getRepository(Clients::class)->findOneBy(['prenom'=>'Interne']);
        $list=array (array('numCompte'=>'100200300401','intitule'=>'OUEDRAOGO HAMADO - Ordinaire', 'typeCompte'=>'o', 'client'=>$cltHamado)
            ,array('numCompte'=>'500600700801','intitule'=>'OUEDRAOGO Moumouni - Ordinaire', 'typeCompte'=>'o', 'client'=>$cltMoumouni)
            ,array('numCompte'=>'100200300402','intitule'=>'OUEDRAOGO HAMADO - Salaire', 'typeCompte'=>'s', 'client'=>$cltHamado)
            ,array('numCompte'=>'500600700802','intitule'=>'OUEDRAOGO Moumouni - Salaire', 'typeCompte'=>'s', 'client'=>$cltMoumouni)
            ,array('numCompte'=>'9900000001','intitule'=>'Intercaisse', 'typeCompte'=>'i', 'client'=>$cltInterne)
            ,array('numCompte'=>'9900000002','intitule'=>'Contre Valeur Devises', 'typeCompte'=>'i', 'client'=>$cltInterne)
            ,array('numCompte'=>'9900000003','intitule'=>'Compense', 'typeCompte'=>'i', 'client'=>$cltInterne)
            ,array('numCompte'=>'9900000004','intitule'=>'Charges Salaires', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'9900000005','intitule'=>'Ecarts de caisse', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'9999999901','intitule'=>'Opérations Caisse 1', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'9999999902','intitule'=>'Opérations Caisse 2', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'9999999903','intitule'=>'Opérations Caisse 3', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'9999999904','intitule'=>'Opérations Caisse 4', 'typeCompte'=>'i', 'client'=>$cltInterne)
        );

        foreach ($list as $listElement) {
            $enr=new Comptes();
            $enr->setNumCompte($listElement['numCompte'])->setIntitule($listElement['intitule'])->setTypeCompte($listElement['typeCompte'])->setClient($listElement['client']);
            $manager->persist($enr);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 20;
    }

}