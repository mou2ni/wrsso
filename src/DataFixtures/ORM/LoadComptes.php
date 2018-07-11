<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 04/07/2018
 * Time: 05:50
 */

namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Comptes;
use App\Entity\Clients;

class LoadComptes extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $cltHamado=$manager->getRepository(Clients::class)->findOneBy(['prenom'=>'Hamado']);
        $cltMoumouni=$manager->getRepository(Clients::class)->findOneBy(['prenom'=>'Moumouni']);
        $cltInterne=$manager->getRepository(Clients::class)->findOneBy(['prenom'=>'Interne']);
        $list=array (array('numCompte'=>'16510000001','intitule'=>'OUEDRAOGO HAMADO - Ordinaire', 'typeCompte'=>'o', 'client'=>$cltHamado)
        ,array('numCompte'=>'16510000002','intitule'=>'OUEDRAOGO Moumouni - Ordinaire', 'typeCompte'=>'o', 'client'=>$cltMoumouni)
        ,array('numCompte'=>'16520000001','intitule'=>'OUEDRAOGO HAMADO - Salaire', 'typeCompte'=>'s', 'client'=>$cltHamado)
        ,array('numCompte'=>'16520000002','intitule'=>'OUEDRAOGO Moumouni - Salaire', 'typeCompte'=>'s', 'client'=>$cltMoumouni)
        ,array('numCompte'=>'585001','intitule'=>'Intercaisse', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'585002','intitule'=>'Compense', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'497000','intitule'=>'Ecarts de caisse', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'497001','intitule'=>'Ecarts caissier 1', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'497002','intitule'=>'Ecarts caissier 2', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'497003','intitule'=>'Ecarts caissier 3', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'571000','intitule'=>'Contre Valeur Devises', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'571001','intitule'=>'Opérations Caisse 1', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'571002','intitule'=>'Opérations Caisse 2', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'571003','intitule'=>'Opérations Caisse 3', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'571004','intitule'=>'Opérations Caisse 4', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'571005','intitule'=>'Caisse menu depenses', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'661000','intitule'=>'Charges Salaires', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'605000','intitule'=>'Charges diverses', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'706000','intitule'=>'Produits divers', 'typeCompte'=>'i', 'client'=>$cltInterne)
        );

        foreach ($list as $listElement) {
            $enr=new Comptes();
            $enr->setNumCompte($listElement['numCompte'])->setIntitule($listElement['intitule'])->setTypeCompte($listElement['typeCompte'])->setClient($listElement['client']);
            $manager->persist($enr);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(LoadClients::class);
    }

}