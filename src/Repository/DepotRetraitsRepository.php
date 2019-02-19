<?php

namespace App\Repository;

use App\Entity\DepotRetraits;
use App\Entity\JourneeCaisses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * CollaborateursRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */

class DepotRetraitsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DepotRetraits::class);
    }


    public function liste($offset=0,$limit = 10)
    {
        $qb = $this->createQueryBuilder('dr')
            ->orderBy('dr.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            //->getQuery()
            //->getResult()
        ;
        $pag = new Paginator($qb);

        return $pag;
    }

    public function findByJourneeCaisse(JourneeCaisses $journeeCaisse,$offset=0,$limit = 10)
    {
        $qb = $this->createQueryBuilder('dr')
            ->select('dr.id as id, dr.dateOperation as dateOperation, cc.intitule as compteClient, dr.libelle as libelle, dr.mDepot as mDepot, dr.mRetrait as mRetrait')
            ->innerJoin('dr.compteClient','cc', 'WITH', 'dr.compteClient= cc.id')
            ->where('dr.journeeCaisse= :journeeCaisse')
            ->setParameter('journeeCaisse',$journeeCaisse)
            ->orderBy('dr.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
        //dump($qb);die();
        //$pag = new Paginator($qb);

        //return $pag;
        return $qb;
    }



}
