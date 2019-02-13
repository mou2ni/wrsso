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

use App\Entity\Collaborateurs;
use App\Entity\Comptes;

class LoadCollaborateurs extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $repCompte = $manager->getRepository(Comptes::class);

        $list=array (
            array('nom'=>'OUEDRAOGO','prenom'=>'HAMADO','mSalaireBase'=>31000,'mIndemLogement'=>9000,'mIndemTransport'=>5000,'mIndemFonction'=>5000,'mIndemAutres'=>3201,'mHeureSup'=>0
            ,'mSecuriteSocialeSalarie'=>2736,'mSecuriteSocialePatronale'=>8512,'mImpotSalarie'=>0,'mTaxePatronale'=>1277,'compteVirement'=>$repCompte->findOneBy(['numCompte'=>'OUEDRAOGO HAMADO - Salaire']), 'estRepresentant'=>true),
            array('nom'=>'OUEDRAOGO','prenom'=>'MOUMOUNI','mSalaireBase'=>42000,'mIndemLogement'=>15000,'mIndemTransport'=>5000,'mIndemFonction'=>5000,'mIndemAutres'=>6860,'mHeureSup'=>28234
            ,'mSecuriteSocialeSalarie'=>5615,'mSecuriteSocialePatronale'=>16335,'mImpotSalarie'=>2768,'mTaxePatronale'=>2450,'compteVirement'=>$repCompte->findOneBy(['numCompte'=>'OUEDRAOGO Moumouni - Salaire']), 'estRepresentant'=>false),
            array('nom'=>'GANOU','prenom'=>'CELINE','mSalaireBase'=>40000,'mIndemLogement'=>15000,'mIndemTransport'=>5000,'mIndemFonction'=>5000,'mIndemAutres'=>3201,'mHeureSup'=>0
            ,'mSecuriteSocialeSalarie'=>3456,'mSecuriteSocialePatronale'=>10912,'mImpotSalarie'=>545,'mTaxePatronale'=>1637,'compteVirement'=>$repCompte->findOneBy(['numCompte'=>'GANOU Celine - Salaire']), 'estRepresentant'=>false),
        );

        foreach ($list as $listElement) {
            $enr=new Collaborateurs();
            $enr->SetNom($listElement['nom'])->setPrenom($listElement['prenom'])->setMSalaireBase($listElement['mSalaireBase'])->setMIndemLogement($listElement['mIndemLogement'])->setMIndemTransport($listElement['mIndemTransport'])
                ->setMIndemFonction($listElement['mIndemFonction'])->setMIndemAutres($listElement['mIndemAutres'])->setMHeureSup($listElement['mHeureSup'])->setMSecuriteSocialeSalarie($listElement['mSecuriteSocialeSalarie'])
                ->setMSecuriteSocialePatronal($listElement['mSecuriteSocialePatronale'])->setMImpotSalarie($listElement['mImpotSalarie'])->setMTaxePatronale($listElement['mTaxePatronale'])->setCompteVirement($listElement['compteVirement'])
                ->setEstRepresentant($listElement['estRepresentant']);
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