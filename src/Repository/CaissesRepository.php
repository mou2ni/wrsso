<?php

namespace App\Repository;

use App\Entity\Caisses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Caisses|null find($id, $lockMode = null, $lockVersion = null)
 * @method Caisses|null findOneBy(array $criteria, array $orderBy = null)
 * @method Caisses[]    findAll()
 * @method Caisses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CaissesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Caisses::class);
    }

    public function getClosedCaisseQb($typeCaisse=Caisses::GUICHET)
    {
         $qb=$this->createQueryBuilder('c');
         if ($typeCaisse==Caisses::GUICHET){
             $qb->where('c.statut=:statut')
                 ->setParameter('statut',Caisses::FERME);
         }
        if($typeCaisse){
            $qb->andWhere('c.typeCaisse=:type')
                ->setParameter('type',$typeCaisse);
        }else{
            $qb->andWhere('c.typeCaisse<>:type')
                ->setParameter('type',Caisses::GUICHET);
        }

        return $qb->orderBy('c.code', 'ASC');

    }

    public function findAllJoinCompteOperation($limit = 10){
        $qb=$this->createQueryBuilder('c');
         $qb->select('c.id, c.libelle, c.code, IDENTITY(c.compteOperation) as compteOperation, co.numCompte')
            ->innerJoin('c.compteOperation','co', 'WITH', 'c.compteOperation= co.id');
        if($limit) $qb->setMaxResults($limit);

        return $qb->getQuery()
            ->getResult()
        ;
    }
    public function liste($limit = 10){
        $qb=$this->createQueryBuilder('c');
        return $qb
            //->select('c.id, c.libelle, c.code, co.numCompte')
            //->innerJoin('c.compteOperation','co', 'WITH', 'c.compteOperation= co.id')
            ->orderBy('c.code', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

//$qb->innerJoin('u.Group', 'g', 'WITH', 'u.status = ?1', 'g.id')
}
