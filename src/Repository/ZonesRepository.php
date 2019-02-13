<?php

namespace App\Repository;

use App\Entity\Zones;
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

class ZonesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Zones::class);
    }

    /**
     * @return Paginator
     */
    public function liste($offset=0,$limit = 10)
    {
        $qb = $this->createQueryBuilder('z')
            ->orderBy('z.ordre', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            //->getQuery()
            //->getResult()
            ;
        $pag = new Paginator($qb);

        return $pag;
    }
    /**
     * @return Zones[]|\Doctrine\ORM\QueryBuilder
     */
    public function trouverZone()
    {
        return $this->createQueryBuilder('zones')
            ->addOrderBy('zones.ordre')
            ->getQuery()->getResult();
    }
}
