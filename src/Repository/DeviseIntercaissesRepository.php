<?php

namespace App\Repository;

use App\Entity\DeviseIntercaisses;
use App\Entity\JourneeCaisses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DeviseIntercaisses|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeviseIntercaisses|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeviseIntercaisses[]    findAll()
 * @method DeviseIntercaisses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviseIntercaissesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DeviseIntercaisses::class);
    }


    /**
     * @param JourneeCaisses $journeeCaisse
     * @return \App\Entity\DeviseIntercaisses[]
     */
    public function findMvtIntercaisseEnvoye(JourneeCaisses $journeeCaisse){



        return $this->findBy(['journeeCaisseSource'=>$journeeCaisse]);

    }


    public function findMvtIntercaisses(JourneeCaisses $journeeCaisse)
    {
        $qb = $this->createQueryBuilder('di');

        // On fait une jointure
        return $qb
            ->innerJoin('di.deviseMouvements', 'dmvt')
            ->addSelect('dmvt')
            ->where($qb->expr()->eq('dmvt.journeeCaisse', ':journeeCaisse'))
            ->setParameter('journeeCaisse', $journeeCaisse)
            ->addOrderBy('dmvt.id', 'DESC')
            ->getQuery()
            ->getResult();

        //return $this->findBy(['journeeCaisseDestination'=>$journeeCaisse]) ->groupBy('di')
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
