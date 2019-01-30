<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Utilisateurs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Utilisateurs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateurs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateurs[]    findAll()
 * @method Utilisateurs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateursRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Utilisateurs::class);
    }


    public function liste($limit = 10)
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.nom', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }


    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
