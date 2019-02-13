<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 04/07/2018
 * Time: 14:19
 */

namespace App\DataFixtures\ORM;

use App\Entity\ParamComptables;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Comptes;
use Proxies\__CG__\App\Entity\JournauxComptables;


class LoadParamComptables extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $compteIntercaisse=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Intercaisse']);
        $compteContreValeurDevise=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Contre Valeur Devises']);
        $compteCompense=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Compense']);
        $compteBaseSalaire=$manager->getRepository(Comptes::class)->findOneBy(['numCompte'=>'661000']);
        $compteIndemSalaire=$manager->getRepository(Comptes::class)->findOneBy(['numCompte'=>'663000']);
        $compteSecSocPat=$manager->getRepository(Comptes::class)->findOneBy(['numCompte'=>'664000']);
        $compteTaxPat=$manager->getRepository(Comptes::class)->findOneBy(['numCompte'=>'641400']);
        $compteOrgaSecSoc=$manager->getRepository(Comptes::class)->findOneBy(['numCompte'=>'431000']);
        $compteOrgaImpSal=$manager->getRepository(Comptes::class)->findOneBy(['numCompte'=>'447200']);
        $compteOrgaTaxPat=$manager->getRepository(Comptes::class)->findOneBy(['numCompte'=>'442100']);
        $compteRemunerationDue=$manager->getRepository(Comptes::class)->findOneBy(['numCompte'=>'422000']);
        $compteEcartCaisse=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Ecarts de caisse']);
        $compteDiversCharge=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Charges diverses']);
        $compteDiversProduit=$manager->getRepository(Comptes::class)->findOneBy(['intitule'=>'Produits divers']);
        $journalPaye=$manager->getRepository(JournauxComptables::class)->findOneBy(['code'=>'PAYE']);

        $enr=new ParamComptables();
        $enr->setCodeStructure('YESBO')->setCompteChargeBaseSalaire($compteBaseSalaire)
            ->setCompteChargeCotiPatronale($compteSecSocPat)
            ->setCompteTaxeSalaire($compteTaxPat)
            ->setCompteChargeFonctSalaire($compteIndemSalaire)
            ->setCompteChargeIndemSalaire($compteIndemSalaire)
            ->setCompteChargeLogeSalaire($compteIndemSalaire)
            ->setCompteChargeTranspSalaire($compteIndemSalaire)
            ->setCompteOrgaImpotSalaire($compteOrgaImpSal)
            ->setCompteOrgaSocial($compteOrgaSecSoc)
            ->setCompteOrgaTaxeSalaire($compteOrgaTaxPat)
            ->setCompteRemunerationDue($compteRemunerationDue)
            ->setCompteCompense($compteCompense)
            ->setCompteContreValeurDevise($compteContreValeurDevise)->setCompteIntercaisse($compteIntercaisse)->setCompteEcartCaisse($compteEcartCaisse)
            ->setCompteDiversCharge($compteDiversCharge)->setCompteDiversProduits($compteDiversProduit)
            ->setJournalPaye($journalPaye);

        $manager->persist($enr);
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            LoadComptes::class,
            LoadJournauxComptables::class,
        );
    }

}