<?php

namespace App\Repository;

use App\Entity\DeviseMouvements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DeviseMouvements|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeviseMouvements|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeviseMouvements[]    findAll()
 * @method DeviseMouvements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviseMouvementsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DeviseMouvements::class);
    }

//    /**
//     * @return DeviseRecus[] Returns an array of DeviseRecus objects
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
    public function findOneBySomeField($value): ?DeviseRecus
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
