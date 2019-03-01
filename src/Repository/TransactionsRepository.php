<?php

namespace App\Repository;

use App\Entity\Transactions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Proxies\__CG__\App\Entity\JournauxComptables;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Transactions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transactions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transactions[]    findAll()
 * @method Transactions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Transactions::class);
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param null $dateDebut
     * @param null $dateFin
     * @return QueryBuilder
     */

    private function _listeQb($offset=0,$limit = 10, $dateDebut=null, $dateFin=null){
        if ($dateFin==null) $dateFin=new \DateTime();
        if ($dateDebut==null) {
            $dateDebut=new \DateTime();;
            $tps_recul=new \DateInterval('P1W');
            $dateDebut->sub($tps_recul);
        }
        return $qb =$this->createQueryBuilder('t')
            //->select('t.id as id, t.dateTransaction, t.numPiece, t.libelle, t.mDebitTotal, t.mCreditTotal')
            ->orderBy('t.updatedAt', 'DESC')
            ->where('t.createdAt <= :dateFin and t.createdAt >= :dateDebut')
            ->setParameter('dateFin',$dateFin)->setParameter('dateDebut',$dateDebut)
            ->setFirstResult($offset)
            ->setMaxResults($limit);
    }


    public function listePaginee($offset=0,$limit = 10, $dateDebut=null, $dateFin=null)
    {
        $qb=$this->_listeQb($offset,$limit,$dateDebut,$dateFin);
        $pag = new Paginator($qb);
        return $pag;
    }

    public function liste($offset=0,$limit = 10, $dateDebut=null, $dateFin=null)
    {
        $qb=$this->_listeQb($offset,$limit,$dateDebut,$dateFin);
        return $qb->getQuery()->getResult();
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
