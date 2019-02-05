<?php

namespace App\Repository;

use App\Entity\Caisses;
use App\Entity\JourneeCaisses;
use App\Entity\Utilisateurs;
use App\Utils\GenererCompta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
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

    public function liste($dateDeb,$dateFin,$caisse = null,$offset,$limit = 10)
    {
         $req = $this->createQueryBuilder('jc');

        if ($dateDeb){
            $req->where('(jc.dateOuv) >=:dateDeb')
                ->setParameter('dateDeb',$dateDeb);
            //dump($req);
        }
        if ($dateFin){
            $req->andWhere('(jc.dateFerm) <= :dateFin')
                ->setParameter('dateFin',$dateFin);
            //dump($req);
        }
        //->andWhere('(jc.dateFerm) <=:dateFin')

         if ($caisse){
             $req->andWhere('jc.caisse=:caisse')
                 ->setParameter('caisse',$caisse);
             //dump($req);
         }
         //die();
        $req->orderBy('jc.dateComptable', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $pag = new Paginator($req);

        return $pag;
    }

    public function getOpenJourneeCaisseQb($dateComptable, $myJournee)
    {
        $qb=$this->createQueryBuilder('jc');
        return $qb->addSelect('c')
            ->innerJoin('jc.caisse', 'c', 'WITH', 'jc.caisse= c.id')
            ->where('jc.statut=:statut')
            ->andWhere('jc.dateComptable=:dateComptable or c.typeCaisse!=:typeCaisse')
            ->andWhere('jc!=:myJournee')
            ->setParameter('statut',JourneeCaisses::ENCOURS)
            ->setParameter('dateComptable',$dateComptable)
            ->setParameter('typeCaisse',Caisses::GUICHET)
            ->setParameter('myJournee',$myJournee)
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

    public function findJourneeCaisses(Caisses $caisse=null, $offset=0, $limit=10, \DateTime $dateDebut=null, \DateTime $dateFin=null)
    {
        if (!$dateFin){
           $now=new \DateTime();
            $dateFin=$now->add(new \DateInterval('P1D'))->format('Y-m-d');
        }//else $dateFin->format('Y-m-d');
        //dateDebut = 1 mois en arrière par rapport à date fin si non fourni
        //$dateDebut=(!$dateDebut)?$dateFin->sub(new \DateInterval('P30D')):$dateDebut;

        $unmois=new \DateInterval('P1M');
        $dateDebut=new \DateTime();
        $dateDebut=$dateDebut->sub($unmois)->format('Y-m-d');
        $qb = $this->createQueryBuilder('jc');
         $qb->where('jc.dateOuv>=:dateDebut and jc.dateOuv<=:dateFin')
            ->andWhere($qb->expr()->neq('jc.statut',':statut'));
        if($caisse){
            $qb->andWhere($qb->expr()->eq('jc.caisse',':caisse'))
            ->setParameter('caisse', $caisse);
        }
         $qb//->addGroupBy('jc.caisse')
            ->addOrderBy('jc.id', 'DESC')
            ->setParameter('dateDebut',$dateDebut)
            ->setParameter('dateFin', $dateFin)
            ->setParameter('statut',JourneeCaisses::INITIAL)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            //->getQuery()
            //->getResult()
             ;
        $pag = new Paginator($qb);
        return $pag;
    }

    public function getJourneeCaissesDuJour(\DateTime $date)
    {
        $qb=$this->createQueryBuilder('jc');
        return $qb
            //->addSelect('c')
            ->innerJoin('jc.caisse', 'c', 'WITH', 'jc.caisse= c.id')
            //->where('jc.statut=:statut')
            ->andWhere('jc.dateComptable=:dateComptable' /*or c.typeCaisse!=:typeCaisse*/)
            //->andWhere('jc!=:myJournee')
            //->setParameter('statut',JourneeCaisses::ENCOURS)
            ->setParameter('dateComptable',$date)
            //->setParameter('typeCaisse',Caisses::GUICHET)
            //->setParameter('myJournee',$myJournee)
            ->getQuery()
            ->getResult();
        ;

    }
    public function getRecapJourneeCaisses(\DateTime $date)
    {
        $qb=$this->createQueryBuilder('jc');
        return $qb
            ->select('jc.dateComptable')
            //->addSelect('jc.dateComptable, SUM(jc.mCvd) ')
            ->addSelect('SUM(jc.mEcartFerm)')
            //->innerJoin('jc.caisse', 'c', 'WITH', 'jc.caisse= c.id')
            //->where('jc.statut=:statut')
            //->andWhere('jc.dateComptable=:dateComptable' /*or c.typeCaisse!=:typeCaisse*/)
            //->andWhere('jc!=:myJournee')
            //->setParameter('statut',JourneeCaisses::ENCOURS)
            //->setParameter('dateComptable',$date)
            //->setParameter('typeCaisse',Caisses::GUICHET)
            //->setParameter('myJournee',$myJournee)
            ->addGroupBy('jc.dateComptable')
            ->addOrderBy('jc.dateComptable')
            ->getQuery()
            ->getResult();
        ;

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

    public function findJourneeCaisseInterneOuvertes(){
         return $this->createQueryBuilder('jc')
             ->select('jc.id as id, c.code as codeCaisse ')
             ->innerJoin('jc.caisse', 'c', 'WITH', 'jc.caisse = c.id')
             ->where('jc.statut= :statut')
             ->andWhere('c.typeCaisse <> :typeCaisse')
             ->setParameter('typeCaisse',Caisses::GUICHET)
             ->setParameter('statut',JourneeCaisses::ENCOURS)
             ->setParameter('statut',JourneeCaisses::ENCOURS)
             ->getQuery()
             ->getArrayResult();
         ;
        /*if ($typeCaisse){
            $qb->andWhere('c.typeCaisse = :typeCaisse')
                ->setParameter('typeCaisse',$typeCaisse);
         }
        return $qb->setParameter('statut',JourneeCaisses::ENCOURS)
            ->getQuery()
            ->getArrayResult();*/


    }
}
