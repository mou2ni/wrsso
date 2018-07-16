<?php
/**
 * Created by Hamado OUEDRAOGO.
 * User: houedraogo
 * Date: 12/07/2018
 * Time: 17:28
 */

namespace App\Tests\Utils;

use App\Entity\Caisses;
use App\Entity\Comptes;
use App\Entity\ParamComptables;
use App\Entity\Transactions;
use App\Entity\TransactionComptes;
use App\Entity\Utilisateurs;

use App\Utils\GenererCompta;

use Symfony\Bridge\PhpUnit\Tests;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

class GenererComptaTest extends Tests
{

    public function testGenComptaEcart()
    {
        $compte=new Comptes();

        $compteRepository=$this->createMock(ObjectRepository::class);
        //$compteRepository->expect()


    }

}
