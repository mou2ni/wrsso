<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 03/07/2018
 * Time: 18:53
 */

namespace App\DataFixtures\ORM;

use App\Entity\Clients;
use App\Entity\Collaborateurs;
use App\Entity\Entreprises;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadEntreprises extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $representant = $manager->getRepository(Collaborateurs::class)->findOneBy(['estRepresentant'=>true]);
        $list=array (
            array('code'=>'YESBO','libelle'=>'YESBO SARL','representant'=>$representant, 'adresse'=>'DAPOYA, Avenue Dimdolobson, 02 BP 6106, Tel : +226 25332205')
        );

        foreach ($list as $entrepris) {
            $entreprise=new Entreprises();
            $entreprise->setCode($entrepris['code'])->setLibelle($entrepris['libelle'])->setAdresse($entrepris['adresse'])->setRepresentant($entrepris['representant']);
            $manager->persist($entreprise);
        }

        $manager->flush();
    }

}