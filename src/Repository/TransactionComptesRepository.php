<?php

namespace App\Repository;

use App\Entity\Comptes;
use App\Entity\JournauxComptables;
use App\Entity\TransactionComptes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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
        $this->select='jc.id, tc.id, t.id, jc.code as codeJournal, t.dateTransaction as dateTransaction, t.numPiece as numPiece, t.libelle as libelleT,tc.numCompte as numCompte, tc.libelle as libelleTC, tc.mDebit, tc.mCredit';
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

    /**
     * @param \DateTime $dateDebut
     * @param \DateTime $dateFin
     * @return QueryBuilder
     */
    private function ecrituresQb($dateDebut=null, $dateFin=null){
        if ($dateFin==null) $dateFin=new \DateTime();
        if ($dateDebut==null) {
            $dateDebut=new \DateTime();;
            $un_mois=new \DateInterval('P1M');
            $dateDebut->sub($un_mois);
        }
         return   $qb =$this->createQueryBuilder('tc')
                ->addSelect($this->select)
                ->innerJoin('tc.transaction','t', 'WITH', 'tc.transaction=t.id')
                ->leftJoin('t.journauxComptable', 'jc', 'WITH','t.journauxComptable=jc.id')
                ->where('t.dateTransaction <= :dateFin and t.dateTransaction >= :dateDebut')
                ->setParameter('dateFin',$dateFin)->setParameter('dateDebut',$dateDebut)
            ;
    }

    public function findEcrituresJournauxComptables($dateDebut=null, $dateFin=null, JournauxComptables $journauxComptable=null,  $offset=0, $limit=20){
        $qb=$this->ecrituresQb($dateDebut,$dateFin);
         if ($journauxComptable)
            $qb->andWhere('t.journauxComptable=:journauxComptable')->setParameter('journauxComptable',$journauxComptable);
        $qb->orderBy('t.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        $pag = new Paginator($qb);
        return $pag;
    }

    public function findEcrituresComptes(Comptes $compte, $dateDebut=null, $dateFin=null, $limit=null){
        $qb=$this->ecrituresQb($dateDebut,$dateFin)
            ->andWhere('tc.compte=:compte')->setParameter('compte',$compte);
        if ($limit) {
            $qb->setMaxResults($limit)
            ->orderBy('t.createdAt', 'DESC');
        } else $qb->orderBy('t.createdAt', 'ASC');

        return  $qb->getQuery()->getResult();
    }

    public function getBalanceComptes($classe=null, $detail=false, $compteDebut=null, $compteFin=null,$dateDebut=null, $dateFin=null){
        $qb =$this->createQueryBuilder('tc')
            ->select('IDENTITY(tc.compte) as compte,tc.numCompte as numCompte, SUM(tc.mDebit) as mDebit, SUM(tc.mCredit) as mCredit');
        if ($detail){
            $qb->innerJoin('tc.compte','c', 'WITH', 'tc.compte=c.id')
                ->innerJoin('tc.transaction','t', 'WITH', 'tc.transaction=t.id')
                ->addSelect('c.intitule as intitule, t.dateTransaction as dateTransaction')
                ;
        }
        if ($classe)  $qb->where('tc.numCompte like \''.$classe.'%\'');
        if ($compteDebut) $qb->andWhere('tc.numCompte>=:compteDebut')->setParameter('compteDebut',$compteDebut);
        if ($compteFin) $qb->andWhere('tc.numCompte<=:compteFin')->setParameter('compteFin',$compteFin);

        $qb->groupBy('tc.compte')
            ->addGroupBy('tc.numCompte');

        if($dateDebut) $qb->having('t.dateTransaction >= :dateDebut')->setParameter('dateDebut',$dateDebut);
        if($dateFin) $qb->andHaving('t.dateTransaction <= :dateFin')->setParameter('dateFin',$dateFin);

         return $qb->addOrderBy('tc.numCompte', 'ASC')
            ->getQuery()
            ->getResult();
    }
/*
    public function findComptesMouvements($dateDebut=null, $dateFin=null){
        if ($dateFin==null) $dateFin=new \DateTime();
        if ($dateDebut==null) {
            $dateDebut=new \DateTime();;
            $un_mois=new \DateInterval('P1M');
            $dateDebut->sub($un_mois);
        }
        return $qb =$this->createQueryBuilder('tc')
            ->addSelect('distinct(tc.numCompte)')
            ->where('t.dateTransaction <= :dateFin and t.dateTransaction >= :dateDebut')
            ->setParameter('dateFin',$dateFin)->setParameter('dateDebut',$dateDebut)
            ->getQuery()->getResult();
    }*/
}
