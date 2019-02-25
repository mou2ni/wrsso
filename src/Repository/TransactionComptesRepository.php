<?php

namespace App\Repository;

use App\Entity\JournauxComptables;
use App\Entity\TransactionComptes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TransactionComptes|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransactionComptes|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransactionComptes[]    findAll()
 * @method TransactionComptes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionComptesRepository extends ServiceEntityRepository
{
    private $select;
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TransactionComptes::class);
        $this->select='jc.id, tc.id, t.id, jc.code as code, t.dateTransaction as dateTransaction, t.numPiece as numPiece, t.libelle as libelleT,tc.numCompte as numCompte, tc.libelle as libelleTC, tc.mDebit, tc.mCredit';
    }


    public function liste($offset=0,$limit = 20)
    {
        /*$qb =$this->createQueryBuilder('tc')
            ->select('t.numPiece, t.libelle, t.dateTransaction, t.mTransaction, tc.numCompte, tc.mDebit, tc.mCredit, tc.soldeAvant')
            ->innerJoin('tc.transaction','t', 'WITH', 'tc.transaction= t.id')
            ->orderBy('t.dateTransaction', 'DESC')
            ->groupBy('t.numPiece')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ;
        $pag = new Paginator($qb);

        return $pag;*/
        return $this->findEcrituresJournauxComptables(null,null,null,$offset,$limit);
    }

    /*public function findDetailTransaction($transaction)
    {
        $qb =$this->createQueryBuilder('tc')
            ->select('t.numPiece as numPiece, t.libelle as libelle, t.dateTransaction as dateTransaction, t.mTransaction as mTransaction, tc.numCompte as numCompte, tc.mDebit as mDebit, tc.mCredit as mCredit, tc.mSoldeAvant as mSoldeAvant')
            ->innerJoin('tc.transaction','t', 'WITH', 'tc.transaction= t.id')
            ->where('tc.transaction=:transaction')
            ->setParameter('transaction',$transaction)
            ->getQuery()
        ;
        return $qb->getResult();
    }*/

    public function getEcriture($transaction_id)
    {
        return $qb =$this->createQueryBuilder('tc')
            ->select($this->select)
            ->innerJoin('tc.transaction','t', 'WITH', 'tc.transaction=t.id')
            ->innerJoin('t.journauxComptable', 'jc', 'WITH','t.journauxComptable=jc.id')
            ->where('t.id=?1')->setParameter(1,$transaction_id)
            ->getQuery()->getResult();
    }

    public function findEcrituresJournauxComptables($dateDebut=null, $dateFin=null, JournauxComptables $journauxComptable=null,  $offset=0, $limit=20){
        if ($dateFin==null) $dateFin=new \DateTime();
        if ($dateDebut==null) {
            $dateDebut=new \DateTime();;
            $un_mois=new \DateInterval('P1M');
            $dateDebut->sub($un_mois);
        }
        //dump($dateFin);dump($dateDebut);

        $qb =$this->createQueryBuilder('tc')
            //->select('tc.id, t.id, jc.id, as id')
            ->addSelect($this->select)
            ->innerJoin('tc.transaction','t', 'WITH', 'tc.transaction=t.id')
            ->innerJoin('t.journauxComptable', 'jc', 'WITH','t.journauxComptable=jc.id')
            ->where('t.dateTransaction <= :dateFin and t.dateTransaction >= :dateDebut');
        if ($journauxComptable)
            $qb->andWhere('t.journauxComptable=:journauxComptable')->setParameter('journauxComptable',$journauxComptable);
        $qb->setParameter('dateFin',$dateFin)->setParameter('dateDebut',$dateDebut)
            ->orderBy('t.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;
        $pag = new Paginator($qb);

        return $pag;

       // return $qb->getQuery()->getResult();
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
