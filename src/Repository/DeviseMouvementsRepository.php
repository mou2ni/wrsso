<?php

namespace App\Repository;

use App\Entity\Caisses;
use App\Entity\DeviseMouvements;
use App\Entity\Devises;
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

    public function getDeviseMouvementPrec(DeviseMouvements $deviseMouvement){
        $req = $this->createQueryBuilder('dm')
            ->join('dm.journeeCaisse','jc')
            ->join('dm.deviseRecu','dr')
            ->where('dm.devise = :devise')
            ->andWhere('jc.caisse = :caisse')
            ->andWhere('dr.dateRecu < :date')
            ->setParameter('devise',$deviseMouvement->getDevise())
            ->setParameter('caisse',$deviseMouvement->getJourneeCaisse()->getCaisse())
            ->setParameter('date',$deviseMouvement->getDeviseRecu()->getDateRecu());
        $req->orderBy('dm.id','DESC');
            //-
        if ($req->getQuery()->getResult()==null)
            return null;
        else
        return $req->getQuery()->getResult()[0];

    }

    public function freqMinId(Devises $devise, Caisses $caisse){
        $reqMinId = $this->createQueryBuilder('dm')
            ->select('MIN(dm.id)')
            ->join('dm.journeeCaisse','jc')
            ->where('dm.devise = :devise')
            ->andWhere('jc.caisse = :caisse')
            ->setParameter('devise',$devise)
            ->setParameter('caisse',$caisse);
            //->getQuery()
            //->getOneOrNullResult();
        $reqMinId->orderBy('a.id', 'DESC');
        $reqMinId->addGroupBy('a.id','d.id');
        return $reqMinId;
    }

    public function getDevisesCVD($agence,$devise, $dateDeb = null, $dateFin = null,$offset,$limit = 10,$uam)
    {
        $reqMinId = $this->createQueryBuilder('d')->select('MIN(d.id)')->getQuery()->getOneOrNullResult();

        $qb = $this->createQueryBuilder('dm')
            ->join('dm.deviseJournee','dj', 'WITH', 'dm.deviseJournee = dj.id')
            ->join('dm.deviseRecu','dr', 'WITH', 'dm.deviseRecu = dr.id')
            ->join('dj.journeeCaisse','jc', 'WITH', 'dj.journeeCaisse = jc.id')
            ->join('jc.caisse','c', 'WITH', 'jc.caisse = c.id')
            ->innerJoin('c.agence','a', 'WITH', 'c.agence = a.id')
            ->join('dj.devise','d', 'WITH', 'dj.devise = d.id')
            ->select('dj.id as id, c.id as idCaisse,c.id as idAgence,d.id as idDevise,  dj.qteOuv as qteOuv,
             SUM(CASE WHEN jc.dateOuv >=:dateDeb and jc.dateFerm <=:dateFin THEN dj.qteAchat ELSE 0 END) as qteAchat ,
             SUM(CASE WHEN jc.dateOuv >=:dateDeb and jc.dateFerm <=:dateFin THEN dj.qteVente ELSE 0 END) as qteVente , c.code as caisse ,a.code as agence , 
             d.code as devise, dr.dateRecu as date,
             dm.soldeOuv as ouverture,
             SUM(CASE WHEN dr.dateRecu >=:dateDeb and dr.dateRecu <=:dateFin and dm.nombre>0 THEN dm.nombre ELSE 0 END) as achat,
             SUM(CASE WHEN dr.dateRecu >=:dateDeb and dr.dateRecu <=:dateFin and dm.nombre<0 THEN dm.nombre ELSE 0 END) as vente, 
             SUM(CASE WHEN dr.dateRecu <=:dateFin AND dr.dateRecu >=:dateDeb THEN dm.nombre ELSE 0 END)+dm.soldeOuv as fermeture, dm.id as mouvement,
              dm.soldeOuv*dm.tauxMoyen as cvouverture,
              SUM(CASE WHEN dr.dateRecu >=:dateDeb and dr.dateRecu <=:dateFin and dm.nombre>0 THEN dm.nombre*dm.taux ELSE 0 END) as cvachat, 
              SUM(CASE WHEN dr.dateRecu >=:dateDeb and dr.dateRecu <=:dateFin and dm.nombre<0 THEN dm.nombre*dm.taux ELSE 0 END) as cvvente, 
              (SUM(CASE WHEN dr.dateRecu <=:dateFin AND dr.dateRecu >=:dateDeb THEN dm.nombre ELSE 0 END)+dm.soldeOuv)*dm.tauxMoyen as cvfermeture,
              SUM(CASE WHEN dr.dateRecu >=:dateDeb and dr.dateRecu <=:dateFin and dm.nombre<0 THEN dm.nombre*(dm.taux-dm.tauxMoyen) ELSE 0 END) as commission')
        ;
        //$qb->where('dm.deviseIntercaisse =:null' );
        //$qb->setParameter('null',null);
        //if ($dateDeb) {
        $qb->where('dr.dateRecu >= :dateDeb');
        $qb->setParameter('dateDeb',$dateDeb);
        //}
        //if ($dateFin) {
        $qb->andWhere('dr.dateRecu <= :dateFin');
        $qb->setParameter('dateFin',$dateFin);
            //->setParameter('minId',1);
        //}
        if ($agence) {
            $qb->andWhere('c.agence =:agence');
            $qb->setParameter('agence',$agence);
        }
        if ($devise) {
            $qb->andWhere('d.id =:devise');
            $qb->setParameter('devise',$devise);
        }
        if ($uam) {
            $qb->andWhere('dm.nombre <>:zero or dm.nombre <>:zero');
            //$qb->andWhere('dj.qteAchat <>:zero');
            $qb->setParameter('zero',0);
        }
        $qb->orderBy('a.id', 'DESC')->setFirstResult($offset)->setMaxResults($limit);
        $qb->addGroupBy('a.id','d.id');
        $pag = new Paginator($qb);
        //dump($pag);die();
        /**/
        //dump($qb->getQuery()->getResult());die();

        return $qb->getQuery()->getResult();
    }
    public function getDevisesCVDSolde($agence,$devise, $dateDeb = null, $dateFin = null,$offset,$limit = 10,$uam)
    {
        $reqMinId = $this->createQueryBuilder('d')->select('MIN(d.id)')->getQuery()->getOneOrNullResult();

        $qb = $this->createQueryBuilder('dm')
            ->join('dm.deviseJournee','dj', 'WITH', 'dm.deviseJournee = dj.id')
            ->join('dm.deviseRecu','dr', 'WITH', 'dm.deviseRecu = dr.id')
            ->join('dj.journeeCaisse','jc', 'WITH', 'dj.journeeCaisse = jc.id')
            ->join('jc.caisse','c', 'WITH', 'jc.caisse = c.id')
            ->innerJoin('c.agence','a', 'WITH', 'c.agence = a.id')
            ->join('dj.devise','d', 'WITH', 'dj.devise = d.id')
            ->select('dj.id as id, c.id as idCaisse,c.id as idAgence,d.id as idDevise,  dj.qteOuv as qteOuv,
            MIN(dm.id)')

        ;
        //$qb->where('dm.deviseIntercaisse =:null' );
        //$qb->setParameter('null',null);
        //if ($dateDeb) {
        //$qb->where('dr.dateRecu >= :dateDeb');
        $qb->setParameter('dateDeb',$dateDeb);
        //}
        //if ($dateFin) {
        //$qb->andWhere('dr.dateRecu <= :dateFin');
        $qb->setParameter('dateFin',$dateFin)
            ->setParameter('minId',1);
        //}
        if ($agence) {
            $qb->andWhere('c.agence =:agence');
            $qb->setParameter('agence',$agence);
        }
        if ($devise) {
            $qb->andWhere('d.id =:devise');
            $qb->setParameter('devise',$devise);
        }
        if ($uam) {
            $qb->andWhere('dm.nombre <>:zero or dm.nombre <>:zero');
            //$qb->andWhere('dj.qteAchat <>:zero');
            $qb->setParameter('zero',0);
        }
        $qb->orderBy('a.id', 'DESC')->setFirstResult($offset)->setMaxResults($limit);
        $qb->addGroupBy('a.id','d.id');
        $pag = new Paginator($qb);
        //dump($pag);die();
        /**/
        dump($qb->getQuery()->getResult());die();

        return $qb->getQuery()->getResult();
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
