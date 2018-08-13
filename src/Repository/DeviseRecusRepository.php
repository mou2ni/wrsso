<?php

namespace App\Repository;

use App\Entity\DeviseRecus;
use App\Entity\JourneeCaisses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DeviseRecus|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeviseRecus|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeviseRecus[]    findAll()
 * @method DeviseRecus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviseRecusRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DeviseRecus::class);
    }

    /**
     * @param JourneeCaisses $journeeCaisse
     * @return mixed
     */
    public function findMyDeviseRecus(JourneeCaisses $journeeCaisse)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.journeeCaisse = :journeeCaisse')
            ->setParameter('journeeCaisse', $journeeCaisse)
            ->orderBy('d.id', 'DESC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult()
            ;
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
