<?php

namespace App\Repository;

use App\Entity\DeviseTmpMouvements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\JourneeCaisses;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DeviseTmpMouvements|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeviseTmpMouvements|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeviseTmpMouvements[]    findAll()
 * @method DeviseTmpMouvements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviseTmpMouvementsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DeviseTmpMouvements::class);
    }

    public function findMvtIntercaisseRecu(JourneeCaisses $journeeCaisse)
    {
        $qb = $this->createQueryBuilder('dmvt');

        // On fait une jointure
        return $qb
            ->innerJoin('dmvt.deviseIntercaisse', 'di')
            ->addSelect('di')
            ->where($qb->expr()->eq('dmvt.journeeCaisse', ':journeeCaisse'))
            ->setParameter('journeeCaisse', $journeeCaisse)
            ->groupBy('di')
            ->addOrderBy('dmvt.id', 'DESC')
            ->getQuery()
            ->getResult();

        //return $this->findBy(['journeeCaisseDestination'=>$journeeCaisse]);

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
