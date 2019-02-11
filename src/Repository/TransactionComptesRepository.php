<?php

namespace App\Repository;

use App\Entity\TransactionComptes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TransactionComptes|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransactionComptes|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransactionComptes[]    findAll()
 * @method TransactionComptes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionComptesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TransactionComptes::class);
    }


    public function liste($offset=0,$limit = 10)
    {
        $qb =$this->createQueryBuilder('tc')
            ->select('t.numPiece, t.libelle, t.dateTransaction, t.mTransaction, tc.numCompte, tc.mDebit, tc.mCredit, tc.soldeAvant')
            ->innerJoin('tc.transaction','t', 'WITH', 'tc.transaction= t.id')
            ->orderBy('t.dateTransaction', 'DESC')
            ->groupBy('t.numPiece')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ;
        $pag = new Paginator($qb);

        return $pag;
    }

    public function findDetailTransaction($transaction)
    {
        $qb =$this->createQueryBuilder('tc')
            ->select('t.numPiece as numPiece, t.libelle as libelle, t.dateTransaction as dateTransaction, t.mTransaction as mTransaction, tc.numCompte as numCompte, tc.mDebit as mDebit, tc.mCredit as mCredit, tc.mSoldeAvant as mSoldeAvant')
            ->innerJoin('tc.transaction','t', 'WITH', 'tc.transaction= t.id')
            ->where('tc.transaction=:transaction')
            ->setParameter('transaction',$transaction)
            ->getQuery()
        ;
        return $qb->getResult();
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
