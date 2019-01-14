<?php

namespace App\Repository;

use App\Entity\Caisses;
use App\Entity\JourneeCaisses;
use App\Entity\Utilisateurs;
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
        $qb=$this->createQueryBuilder('jc');
        return $qb->addSelect('c')
            ->innerJoin('jc.caisse', 'c', 'WITH', 'jc.caisse= c.id')
            ->where('jc.statut=:statut')
            ->setParameter('statut',JourneeCaisses::ENCOURS)
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

    public function findJourneeActive( Utilisateurs $utilisateur)
    {
        $qb = $this->createQueryBuilder('j');

        return $qb
            ->where($qb->expr()->eq('j.utilisateur', ':utilisateur'))
            ->andWhere($qb->expr()->isNotNull('j.dateFerm'))
            //->andWhere($qb->expr()->eq('j.statut',':ferme'))
            ->addOrderBy('j.dateFerm','DESC')
            ->setParameters(['utilisateur'=>$utilisateur])
            ->getQuery()
            ->getResult();
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

    public function findLastJournee( Caisses $caisse)
    {
        $qb = $this->createQueryBuilder('j');

        return $qb
            ->where($qb->expr()->eq('j.caisse', ':caisse'))
            //->andWhere($qb->expr()->isNull('j.journeeSuivante'))
            ->andWhere($qb->expr()->eq('j.statut', ':ouvert'))
            ->addOrderBy('j.id', 'DESC')
            ->setParameters(['caisse' => $caisse, 'ouvert' => JourneeCaisses::INITIAL])
            ->getQuery()
            ->getResult();
        //->getSQL();
    }

    public function getJourneeCaissesDuJour(\DateTime $date)
    {
        $dateDeb=new \DateTime();
        $dateFin=new \DateTime();
        $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('d'));
        $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('d'));
        $dateDeb->setTime($date->format('00'),$date->format('00'),$date->format('00'),$date->format('00'));
        $dateFin->setTime($date->format('23'),$date->format('59'),$date->format('59'),$date->format('999999'));
        $qb = $this->createQueryBuilder('j');
        return $qb
            //->select('j')
            //->from('MyAppNameBundle:Entity','entities')
            ->where('(j.dateOuv) >= :dateDeb')
            ->andWhere('(j.dateOuv) <= :dateFin')
            ->orderBy('j.dateOuv','DESC')
            ->setParameter('dateDeb', $dateDeb)
            ->setParameter('dateFin', $dateFin)
            ->getQuery()
            ->getResult();
    }

    public function getJourneesDeCaisse(Caisses $caisse, \DateTime $dateDeb0, \DateTime $dateFin0)
    {
        $dateDeb=new \DateTime();
        $dateFin=new \DateTime();
        $dateDeb->setDate($dateDeb0->format('Y'),$dateDeb0->format('m'),$dateDeb0->format('d'));
        $dateFin->setDate($dateFin0->format('Y'),$dateFin0->format('m'),$dateFin0->format('d'));
        $dateDeb->setTime($dateDeb0->format('00'),$dateDeb0->format('00'),$dateDeb0->format('00'),$dateDeb0->format('00'));
        $dateFin->setTime($dateFin0->format('23'),$dateFin0->format('59'),$dateFin0->format('59'),$dateFin0->format('999999'));
        $qb = $this->createQueryBuilder('j');
        return $qb
            //->select('j')
            //->from('MyAppNameBundle:Entity','entities')
            ->where('(j.dateOuv) >= :dateDeb')
            ->andWhere('(j.dateOuv) <= :dateFin')
            ->andWhere('(j.caisse) = :caisse')
            ->andWhere('j.statut<> :statut')
            ->orderBy('j.id','DESC')
            ->setParameter('dateDeb', $dateDeb)
            ->setParameter('dateFin', $dateFin)
            ->setParameter('caisse', $caisse)
            ->setParameter('statut', JourneeCaisses::INITIAL)
            ->getQuery()
            ->getResult();
    }

   /* public function findMyIntercaisseEntrant(JourneeCaisses $journeeCaisse){
        $qb = $this->createQueryBuilder('jc');
        return $qb->select('c.code, ie.journeeCaisseSortant, ie.montant')
            ->from('JourneeCaisse',(''))
            ->innerJoin('jc.intercaisseEntrants', 'ie')
            ->innerJoin()
    }*/



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
