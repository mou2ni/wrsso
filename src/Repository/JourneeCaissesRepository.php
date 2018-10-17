<?php

namespace App\Repository;

use App\Entity\Caisses;
use App\Entity\JourneeCaisses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function getOpenJourneeCaisseQb()

    {
        return $this
            ->createQueryBuilder('jc')
            ->where('jc.statut=:statut')
            ->setParameter('statut',JourneeCaisses::OUVERT)
            ;

    }

   
    public function findOneJourneeActive( Caisses $caisse)
    {
        $qb = $this->createQueryBuilder('j');

        return $qb
            ->where($qb->expr()->eq('j.caisse', ':caisse'))
            ->andWhere($qb->expr()->isNull('j.journeeSuivante'))
            ->andWhere($qb->expr()->eq('j.statut',':ouvert'))
            ->setParameters(['caisse'=>$caisse,'ouvert'=>JourneeCaisses::INITIAL])
            ->getQuery()
            ->getFirstResult();
    }

    public function findOneJourneeActive1( Caisses $caisse)
    {
        $qb = $this->createQueryBuilder('j');

        return $qb
            -->where($qb->expr()->eq('j.caisse', ':caisse'))
                ->andWhere($qb->expr()->isNull('j.journeeSuivante'))
                ->andWhere($qb->expr()->eq('j.statut',':initial'))
                ->orWhere($qb->expr()->eq('j.statut',':ouvert'))
                ->setParameters(['caisse'=>$caisse, 'initial'=>JourneeCaisses::INITIAL,'ouvert'=>JourneeCaisses::OUVERT])
                ->getQuery()
                ->getOneOrNullResult();
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
