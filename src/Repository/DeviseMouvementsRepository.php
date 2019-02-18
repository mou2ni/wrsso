<?php

namespace App\Repository;

use App\Entity\DeviseMouvements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Proxies\__CG__\App\Entity\JourneeCaisses;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DeviseMouvements|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeviseMouvements|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeviseMouvements[]    findAll()
 * @method DeviseMouvements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviseMouvementsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DeviseMouvements::class);
    }

    public function findMvtIntercaisseRecu(JourneeCaisses $journeeCaisse)
    {
        $qb = $this->createQueryBuilder('dmvt');

        // On fait une jointure
        return $qb
            ->innerJoin('dmvt.deviseIntercaisse', 'di')
            ->addSelect('di')
            ->where($qb->expr()->eq('dmvt.journeeCaisse', 'di.journeeCaisseDestination'))
            ->andWhere($qb->expr()->eq('dmvt.journeeCaisse', ':journeeCaisse'))
            ->setParameter('journeeCaisse', $journeeCaisse)
            ->groupBy('di')
            ->addOrderBy('dmvt.id', 'DESC')
            ->getQuery()
            ->getResult();

        //return $this->findBy(['journeeCaisseDestination'=>$journeeCaisse]);

    }
    public function findMouvement($dateDeb,$dateFin,$offset,$limit = 10)
    {
        $req = $this->createQueryBuilder('dmvt');
        $req = $req->leftJoin('dmvt.journeeCaisse','jc');

        if ($dateDeb){
            $req->where('(jc.dateComptable) >=:dateDeb')
                ->setParameter('dateDeb',$dateDeb);
            //dump($req);
        }
        if ($dateFin){
            $req->andWhere('(jc.dateComptable) <= :dateFin')
                ->setParameter('dateFin',$dateFin);
            //dump($req);
        }
        /*//->andWhere('(jc.dateFerm) <=:dateFin')

        if ($caisse){
            $req->andWhere('jc.caisse=:caisse')
                ->setParameter('caisse',$caisse);
            //dump($req);
        }
        //die();*/
        //->orderBy('jc.dateComptable', 'DESC')
           $req ->setFirstResult($offset)
            ->setMaxResults($limit);

        $pag = new Paginator($req);

        return $pag;
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
