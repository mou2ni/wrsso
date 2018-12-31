<?php

namespace App\Repository;

use App\Entity\Intercaisses;
use App\Entity\JourneeCaisses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DeviseIntercaisses|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeviseIntercaisses|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeviseIntercaisses[]    findAll()
 * @method DeviseIntercaisses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntercaissesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Intercaisses::class);
    }
    
    public function findMyIntercaisses(){
        
    }

    public function findMvtIntercaisses(JourneeCaisses $journeeCaisse)
    {
        $qb = $this->createQueryBuilder('i');

        // On fait une jointure
        return $qb
            ->innerJoin('i.deviseMouvements', 'jc')
            ->addSelect('dmvt')
            ->where($qb->expr()->eq('dmvt.journeeCaisse', ':journeeCaisse'))
            ->setParameters(['journeeCaisse'=>$journeeCaisse])
            ->addOrderBy('dmvt.id', 'DESC')
            ->getQuery()
            ->getResult();

        //return $this->findBy(['journeeCaisseDestination'=>$journeeCaisse]) ->groupBy('di')
        ;

    }

    public function findTmpMvtIntercaisses(JourneeCaisses $journeeCaisse)
    {
        $qb = $this->createQueryBuilder('di');

        $statut=array(DeviseIntercaisses::ANNULE,DeviseIntercaisses::VALIDE);

        // On fait une jointure
        return $qb
            ->innerJoin('di.deviseTmpMouvements', 'dmvt')
            ->addSelect('dmvt')
            ->where($qb->expr()->eq('di.journeeCaisseSource', ':journeeCaisseSource'))
            ->orWhere($qb->expr()->eq('di.journeeCaisseDestination', ':journeeCaisseDestination'))
            ->andWhere($qb->expr()->notIn('di.statut', ':statut'))
            ->setParameters(['journeeCaisseSource'=>$journeeCaisse,'statut'=>$statut,'journeeCaisseDestination'=>$journeeCaisse])
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
