<?php

namespace App\Repository;

use App\Entity\DepotRetrait1;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DepotRetrait1|null find($id, $lockMode = null, $lockVersion = null)
 * @method DepotRetrait1|null findOneBy(array $criteria, array $orderBy = null)
 * @method DepotRetrait1[]    findAll()
 * @method DepotRetrait1[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepotRetrait1Repository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DepotRetrait1::class);
    }

//    /**
//     * @return DepotRetrait1[] Returns an array of DepotRetrait1 objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DepotRetrait1
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
