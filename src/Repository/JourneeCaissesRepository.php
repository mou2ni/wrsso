<?php

namespace App\Repository;

use App\Entity\JourneeCaisses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use function Sodium\add;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JourneeCaisses|null find($id, $lockMode = null, $lockVersion = null)
 * @method JourneeCaisses|null findOneBy(array $criteria, array $orderBy = null)
 * @method JourneeCaisses[]    findAll()
 * @method JourneeCaisses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JourneeCaissesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JourneeCaisses::class);
    }

    public function getOpenJourneeCaisseQb( $statut)

    {
        return $this
            ->createQueryBuilder('jc')
            ->where('jc.statut=:statut')
            ->setParameter('statut',$statut)
            ;

    }

    /**
     * @return int []
     */
    public function getOpenJourneeCaisse()
    {
        return $this
            ->createQueryBuilder('jc')
            ->innerJoin('jc.idCaisse','c')
            ->select('c.id as caisse')
            ->andWhere('jc.statut=:statut')
            ->setParameter('statut',JourneeCaisses::OUVERT)
            ->getQuery()
            ->getResult();
            //;
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
