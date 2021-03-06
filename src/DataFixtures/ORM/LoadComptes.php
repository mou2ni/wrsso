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
        $repClent = $manager->getRepository(Clients::class);
        $cltInterne=$repClent->findOneBy(['prenom'=>'Interne']);
        /*$cltHamado=$repClent->findOneBy(['prenom'=>'Hamado']);
        $cltMoumouni=$manager->getRepository(Clients::class)->findOneBy(['prenom'=>'Moumouni']);
        $cltCeline=$manager->getRepository(Clients::class)->findOneBy(['prenom'=>'Celine']);
        $cltHamidou=$manager->getRepository(Clients::class)->findOneBy(['prenom'=>'hamidou']);
        */
        $list=array (
            ///COMPTES ORDINAIRES
            array('numCompte'=>'16510001','intitule'=>'OUEDRAOGO HAMADO - Ordinaire', 'typeCompte'=>Comptes::ORDINAIRE, 'client'=>$repClent->findOneBy(['prenom'=>'Hamado']))
        ,array('numCompte'=>'16510002','intitule'=>'OUEDRAOGO Moumouni - Ordinaire', 'typeCompte'=>Comptes::ORDINAIRE, 'client'=>$repClent->findOneBy(['prenom'=>'Moumouni']))
        ,array('numCompte'=>'16510003','intitule'=>'GANOU Celine - Ordinaire', 'typeCompte'=>Comptes::ORDINAIRE, 'client'=>$repClent->findOneBy(['prenom'=>'Celine']))
        ,array('numCompte'=>'16510004','intitule'=>'OUEDRAOGO Hamidou - Ordinaire', 'typeCompte'=>Comptes::ORDINAIRE, 'client'=>$repClent->findOneBy(['prenom'=>'Hamidou']))
        ,array('numCompte'=>'16510005','intitule'=>'YESBO Tontine - Ordinaire', 'typeCompte'=>Comptes::ORDINAIRE, 'client'=>$repClent->findOneBy(['prenom'=>'Tontine']))
        ,array('numCompte'=>'16510006','intitule'=>'OUEDRAOGO Bassirou - Ordinaire', 'typeCompte'=>Comptes::ORDINAIRE, 'client'=>$repClent->findOneBy(['prenom'=>'Bassirou']))
        ,array('numCompte'=>'16510007','intitule'=>'OUEDRAOGO/KANYALA Lucie - Ordinaire', 'typeCompte'=>Comptes::ORDINAIRE, 'client'=>$repClent->findOneBy(['prenom'=>'Lucie']))
        ,array('numCompte'=>'16510008','intitule'=>'OUEDRAOGO Sidiki - Ordinaire', 'typeCompte'=>Comptes::ORDINAIRE, 'client'=>$repClent->findOneBy(['prenom'=>'Sidiki']))
        ,array('numCompte'=>'16510009','intitule'=>'BANK Coris - Pret', 'typeCompte'=>Comptes::ORDINAIRE, 'client'=>$repClent->findOneBy(['prenom'=>'Coris']))
        ,array('numCompte'=>'16510010','intitule'=>'OUEDRAOGO Lassina - Ordinaire', 'typeCompte'=>Comptes::ORDINAIRE, 'client'=>$repClent->findOneBy(['prenom'=>'Lassina']))

            ///COMPTES DE SALAIRES
        ,array('numCompte'=>'16520001','intitule'=>'OUEDRAOGO HAMADO - Salaire', 'typeCompte'=>Comptes::SALAIRE, 'client'=>$repClent->findOneBy(['prenom'=>'Hamado']))
        ,array('numCompte'=>'16520002','intitule'=>'OUEDRAOGO Moumouni - Salaire', 'typeCompte'=>Comptes::SALAIRE, 'client'=>$repClent->findOneBy(['prenom'=>'Moumouni']))
        ,array('numCompte'=>'16520003','intitule'=>'GANOU Celine - Salaire', 'typeCompte'=>Comptes::SALAIRE, 'client'=>$repClent->findOneBy(['prenom'=>'Celine']))
        ,array('numCompte'=>'16520004','intitule'=>'OUEDRAOGO Lassina - Salaire', 'typeCompte'=>Comptes::SALAIRE, 'client'=>$repClent->findOneBy(['prenom'=>'Lassina']))
        ,array('numCompte'=>'16520005','intitule'=>'TOUBRI Ghislaine - Salaire', 'typeCompte'=>Comptes::SALAIRE, 'client'=>$repClent->findOneBy(['prenom'=>'Ghislaine']))
        ,array('numCompte'=>'16520006','intitule'=>'BAYOULOU Ami - Salaire', 'typeCompte'=>Comptes::SALAIRE, 'client'=>$repClent->findOneBy(['prenom'=>'Ami']))
        ,array('numCompte'=>'16520007','intitule'=>'SAKANDE Aina - Salaire', 'typeCompte'=>Comptes::SALAIRE, 'client'=>$repClent->findOneBy(['prenom'=>'Aina']))
        ,array('numCompte'=>'16520008','intitule'=>'SORE Fatim - Salaire', 'typeCompte'=>Comptes::SALAIRE, 'client'=>$repClent->findOneBy(['prenom'=>'Fatim']))
        ,array('numCompte'=>'16520009','intitule'=>'TAMBOURA Djanna - Salaire', 'typeCompte'=>Comptes::SALAIRE, 'client'=>$repClent->findOneBy(['prenom'=>'Djanna']))
        ,array('numCompte'=>'16520010','intitule'=>'COULIBALY Djeneba - Salaire', 'typeCompte'=>Comptes::SALAIRE, 'client'=>$repClent->findOneBy(['prenom'=>'Djeneba']))
        ,array('numCompte'=>'16520011','intitule'=>'ZONOU Amidou - Salaire', 'typeCompte'=>Comptes::SALAIRE, 'client'=>$repClent->findOneBy(['prenom'=>'Amidou']))

            ///COMPTES INTERNES DE YESBO
        ,array('numCompte'=>'431000','intitule'=>'Organisme de sécurité sociales', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'422000','intitule'=>'Remuneration due', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'442100','intitule'=>'Organisme Taxes sur salaire', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'447200','intitule'=>'Organisme Impot sur salaire', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)

        ,array('numCompte'=>'521001','intitule'=>'Coris Bank Gestion', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        //,array('numCompte'=>'521002','intitule'=>'Coris Bank Pret', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'521003','intitule'=>'Coris Bank Compense', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'521004','intitule'=>'Coris Bank Commission', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'585000','intitule'=>'Intercaisse', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'585001','intitule'=>'Compense', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)

        ,array('numCompte'=>'599000','intitule'=>'Ecarts de caisse', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'599001','intitule'=>'Ecarts caissier Celine', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'599002','intitule'=>'Ecarts caissier Lassina', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'599003','intitule'=>'Ecarts caissier Ghislaine', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'599004','intitule'=>'Ecarts caissier Ami', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'599005','intitule'=>'Ecarts caissier Aina', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'599006','intitule'=>'Ecarts caissier Fatim', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'599007','intitule'=>'Ecarts caissier Djanna', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)

        ,array('numCompte'=>'5711000','intitule'=>'Contre Valeur Devises', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'5711001','intitule'=>'Opérations Caisse 1', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'5711002','intitule'=>'Opérations Caisse 2', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'5711003','intitule'=>'Opérations Caisse 3', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'5711004','intitule'=>'Opérations Caisse 4', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'5711005','intitule'=>'Caisse menu depenses', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'5711006','intitule'=>'Opérations Caisse 0', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)

        ,array('numCompte'=>'5712000','intitule'=>'CV devise Caisse ', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'5712001','intitule'=>'CV devise Caisse 1', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'5712002','intitule'=>'CV devise Caisse 2', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'5712003','intitule'=>'CV devise Caisse 3', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'5712004','intitule'=>'CV devise Caisse 4', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'5712005','intitule'=>'CV devise Caisse 0', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)

        ,array('numCompte'=>'661000','intitule'=>'Salaire de base du personnel', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'663000','intitule'=>'Indemnités du personnel', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'664000','intitule'=>'Charges sociales patronale', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'641400','intitule'=>'Taxes patronales salaire', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'601000','intitule'=>'Achats de marchandises', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'605000','intitule'=>'Achats divers', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'610000','intitule'=>'Transport', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'622000','intitule'=>'Loyer', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'624000','intitule'=>'Entretiens et réparation', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'627000','intitule'=>'Publicité et relation publique', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'628000','intitule'=>'Frais de télécommunication', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'631000','intitule'=>'Frais bancaires', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'640000','intitule'=>'Impots et taxes', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'670000','intitule'=>'Frais financiers', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)

        ,array('numCompte'=>'701000','intitule'=>'Vente de marchandises', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'705000','intitule'=>'Travaux facturés', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'706000','intitule'=>'COMMISSIONS', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
       /* ,array('numCompte'=>'706001','intitule'=>'COMMISSIONS OM Dealer', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'706002','intitule'=>'COMMISSIONS OM KD01', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'706003','intitule'=>'COMMISSIONS OM KD02', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'706004','intitule'=>'COMMISSIONS OM KD03', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'706005','intitule'=>'COMMISSIONS WU', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'706006','intitule'=>'COMMISSIONS MGR', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'706007','intitule'=>'COMMISSIONS RIA', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'706008','intitule'=>'COMMISSIONS SW', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        ,array('numCompte'=>'706009','intitule'=>'COMMISSIONS Autres Transferts', 'typeCompte'=>Comptes::INTERNE, 'client'=>$cltInterne)
        */
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