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

class LoadComptes extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $repClent = $manager->getRepository(Clients::class);
        $cltInterne=$repClent->findOneBy(['prenom'=>'Interne']);
        /*$cltHamado=$repClent->findOneBy(['prenom'=>'Hamado']);
        $cltMoumouni=$manager->getRepository(Clients::class)->findOneBy(['prenom'=>'Moumouni']);
        $cltCeline=$manager->getRepository(Clients::class)->findOneBy(['prenom'=>'Celine']);
        $cltHamidou=$manager->getRepository(Clients::class)->findOneBy(['prenom'=>'hamidou']);
        */
        $list=array (
            ///COMPTES ORDINAIRES
            array('numCompte'=>'16510001','intitule'=>'OUEDRAOGO HAMADO - Ordinaire', 'typeCompte'=>'o', 'client'=>$repClent->findOneBy(['prenom'=>'Hamado']))
        ,array('numCompte'=>'16510002','intitule'=>'OUEDRAOGO Moumouni - Ordinaire', 'typeCompte'=>'o', 'client'=>$repClent->findOneBy(['prenom'=>'Moumouni']))
        ,array('numCompte'=>'16510003','intitule'=>'GANOU Celine - Ordinaire', 'typeCompte'=>'o', 'client'=>$repClent->findOneBy(['prenom'=>'Celine']))
        ,array('numCompte'=>'16510004','intitule'=>'OUEDRAOGO Hamidou - Ordinaire', 'typeCompte'=>'o', 'client'=>$repClent->findOneBy(['prenom'=>'Hamidou']))
        ,array('numCompte'=>'16510005','intitule'=>'YESBO Tontine - Ordinaire', 'typeCompte'=>'o', 'client'=>$repClent->findOneBy(['prenom'=>'Tontine']))
        ,array('numCompte'=>'16510006','intitule'=>'OUEDRAOGO Bassirou - Ordinaire', 'typeCompte'=>'o', 'client'=>$repClent->findOneBy(['prenom'=>'Bassirou']))
        ,array('numCompte'=>'16510007','intitule'=>'OUEDRAOGO/KANYALA Lucie - Ordinaire', 'typeCompte'=>'o', 'client'=>$repClent->findOneBy(['prenom'=>'Lucie']))
        ,array('numCompte'=>'16510008','intitule'=>'OUEDRAOGO Sidiki - Ordinaire', 'typeCompte'=>'o', 'client'=>$repClent->findOneBy(['prenom'=>'Sidiki']))
        ,array('numCompte'=>'16510009','intitule'=>'BANK Coris - Ordinaire', 'typeCompte'=>'o', 'client'=>$repClent->findOneBy(['prenom'=>'Coris']))
        ,array('numCompte'=>'16510010','intitule'=>'OUEDRAOGO Lassina - Ordinaire', 'typeCompte'=>'o', 'client'=>$repClent->findOneBy(['prenom'=>'Lassina']))

            ///COMPTES DE SALAIRES
        ,array('numCompte'=>'16520001','intitule'=>'OUEDRAOGO HAMADO - Salaire', 'typeCompte'=>'s', 'client'=>$repClent->findOneBy(['prenom'=>'Hamado']))
        ,array('numCompte'=>'16520002','intitule'=>'OUEDRAOGO Moumouni - Salaire', 'typeCompte'=>'s', 'client'=>$repClent->findOneBy(['prenom'=>'Moumouni']))
        ,array('numCompte'=>'16520003','intitule'=>'GANOU Celine - Salaire', 'typeCompte'=>'s', 'client'=>$repClent->findOneBy(['prenom'=>'Celine']))
        ,array('numCompte'=>'16520004','intitule'=>'OUEDRAOGO Lassina - Salaire', 'typeCompte'=>'s', 'client'=>$repClent->findOneBy(['prenom'=>'Lassina']))
        ,array('numCompte'=>'16520005','intitule'=>'TOUBRI Ghislaine - Salaire', 'typeCompte'=>'s', 'client'=>$repClent->findOneBy(['prenom'=>'Ghislaine']))
        ,array('numCompte'=>'16520006','intitule'=>'BAYOULOU Ami - Salaire', 'typeCompte'=>'s', 'client'=>$repClent->findOneBy(['prenom'=>'Ami']))
        ,array('numCompte'=>'16520007','intitule'=>'SAKANDE Aina - Salaire', 'typeCompte'=>'s', 'client'=>$repClent->findOneBy(['prenom'=>'Aina']))
        ,array('numCompte'=>'16520008','intitule'=>'SORE Fatim - Salaire', 'typeCompte'=>'s', 'client'=>$repClent->findOneBy(['prenom'=>'Fatim']))
        ,array('numCompte'=>'16520009','intitule'=>'TAMBOURA Djanna - Salaire', 'typeCompte'=>'s', 'client'=>$repClent->findOneBy(['prenom'=>'Djanna']))
        ,array('numCompte'=>'16520010','intitule'=>'COULIBALY Djeneba - Salaire', 'typeCompte'=>'s', 'client'=>$repClent->findOneBy(['prenom'=>'Djeneba']))
        ,array('numCompte'=>'16520011','intitule'=>'ZONOU Amidou - Salaire', 'typeCompte'=>'s', 'client'=>$repClent->findOneBy(['prenom'=>'Amidou']))

            ///COMPTES INTERNES DE YESBO
        ,array('numCompte'=>'585000','intitule'=>'Intercaisse', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'585001','intitule'=>'Compense', 'typeCompte'=>'i', 'client'=>$cltInterne)

        ,array('numCompte'=>'599000','intitule'=>'Ecarts de caisse', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'599001','intitule'=>'Ecarts caissier Celine', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'599002','intitule'=>'Ecarts caissier Lassina', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'599003','intitule'=>'Ecarts caissier Ghislaine', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'599004','intitule'=>'Ecarts caissier Ami', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'599005','intitule'=>'Ecarts caissier Aina', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'599006','intitule'=>'Ecarts caissier Fatim', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'599007','intitule'=>'Ecarts caissier Djanna', 'typeCompte'=>'i', 'client'=>$cltInterne)

        ,array('numCompte'=>'5711000','intitule'=>'Contre Valeur Devises', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'5711001','intitule'=>'Opérations Caisse 1', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'5711002','intitule'=>'Opérations Caisse 2', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'5711003','intitule'=>'Opérations Caisse 3', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'5711004','intitule'=>'Opérations Caisse 4', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'5711005','intitule'=>'Caisse menu depenses', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'5711006','intitule'=>'Opérations Caisse 0', 'typeCompte'=>'i', 'client'=>$cltInterne)

        ,array('numCompte'=>'5712000','intitule'=>'CV devise Caisse ', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'5712001','intitule'=>'CV devise Caisse 1', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'5712002','intitule'=>'CV devise Caisse 2', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'5712003','intitule'=>'CV devise Caisse 3', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'5712004','intitule'=>'CV devise Caisse 4', 'typeCompte'=>'i', 'client'=>$cltInterne)
        ,array('numCompte'=>'5712005','intitule'=>'CV devise Caisse 0', 'typeCompte'=>'i', 'client'=>$cltInterne)

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
        return array(
            LoadClients::class,
        );
    }


}