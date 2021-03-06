<?php

namespace App\Repository;

use App\Entity\DeviseJournees;
use App\Entity\Devises;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @method DeviseJournees|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeviseJournees|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeviseJournees[]    findAll()
 * @method DeviseJournees[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviseJourneesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DeviseJournees::class);
    }

    public function liste($limit = 10)
    {
         $qb = $this->createQueryBuilder('b')
            //->orderBy('b.id', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
        return $qb;
    }
    public function getDevises($caisse,$devise, $dateDeb = null, $dateFin = null,$offset,$limit = 10)
    {
        $qb = $this->createQueryBuilder('dj')
            ->innerJoin('dj.journeeCaisse','jc', 'WITH', 'dj.journeeCaisse = jc.id')
            ->innerJoin('jc.caisse','c', 'WITH', 'jc.caisse = c.id')
            ->innerJoin('dj.devise','d', 'WITH', 'dj.devise = d.id')
            ->leftJoin('dj.deviseJourneePrecedente','djp', 'WITH', 'dj.deviseJourneePrecedente=djp.id')
            ->addSelect('dj.id as id, c.id as idCaisse,d.id as idDevise,  jc.dateOuv as dateOuv , jc.dateFerm as dateFerm , c.code as caisse , d.code as devise, dj.qteOuv as qteOuv, dj.qteAchat as qteAchat, dj.qteVente as qteVente,dj.qteIntercaisse as qteIntercaisse, dj.qteFerm as qteFerm, dj.mCvdVente - dj.mCvdVente as cvd, djp.qteFerm-dj.qteOuv as ecartOuv, dj.qteFerm - (dj.qteOuv +dj.qteAchat+dj.qteVente+dj.qteIntercaisse) as ecartFerm')
            ;
        if ($dateDeb) {
            $qb->where('jc.dateOuv >= :dateDeb');
            $qb->setParameter('dateDeb',$dateDeb);
        }
        if ($dateFin) {
            $qb->andWhere('jc.dateFerm <= :dateFin');
            $qb->setParameter('dateFin',$dateFin);
        }
        if ($caisse) {
            $qb->andWhere('jc.caisse =:caisse');
            $qb->setParameter('caisse',$caisse);
        }
        if ($devise) {
            $qb->andWhere('dj.devise =:devise');
            $qb->setParameter('devise',$devise);
        }
        /*if ($ujm) {
            $qb->andWhere('dj.qteVente <>:zero or dj.qteAchat <>:zero');
            //$qb->andWhere('dj.qteAchat <>:zero');
            $qb->setParameter('zero',0);
        }*/
        $qb
            ->orderBy('dj.id', 'ASC')
            //->groupBy('c.agence','d.id')
            ->setFirstResult($offset)->setMaxResults($limit);
        $pag = new Paginator($qb);
        //dump($pag);die();
        /**/
//dump($qb->getQuery()->getResult());die();

        return $pag;
    }

    public function getDeviseTresorerie(\DateTime $dateDeb, \DateTime $dateFin){
        $debut = $dateDeb->format('Y/m/d');
        $fin = $dateFin->format('Y/m/d');
        $em = $this->getEntityManager();
        $req = "SELECT d.id as id,d.code as devise, 
(SELECT SUM(dj.qte_ouv)  FROM DeviseJournees dj 
 WHERE dj.idJourneeCaisse IN 
 (SELECT jc.id FROM JourneeCaisses jc, JourneeCaisses jcp
 WHERE jc.journee_precedente_id=jcp.id AND jcp.date_ferm<'$debut' AND jc.date_ouv >= '$debut' AND jc.date_ferm <= '$fin'
 UNION
SELECT jcp.id FROM JourneeCaisses jcp
WHERE jcp.date_ferm < '$debut' AND (NOT EXISTS (SELECT * FROM JourneeCaisses j WHERE j.journee_precedente_id=jcp.id)))
 AND dj.devise_id = d.id
 GROUP BY dj.devise_id
) AS qteOuv,

(SELECT SUM(dj.qte_achat) FROM DeviseJournees dj
WHERE dj.idJourneeCaisse 
IN (SELECT jc.id FROM JourneeCaisses jc
WHERE jc.date_ouv >= '$debut' AND jc.date_ferm <= '$fin' AND dj.devise_id = d.id)
GROUP BY dj.devise_id) AS achat,

 (SELECT SUM(dj.qte_vente) FROM DeviseJournees dj
WHERE dj.idJourneeCaisse 
IN (SELECT jc.id FROM JourneeCaisses jc
WHERE jc.date_ouv >= '$debut' AND jc.date_ferm <= '$fin' AND dj.devise_id = d.id)
GROUP BY dj.devise_id) AS vente,

(SELECT SUM(dj.qte_ferm) FROM DeviseJournees dj
WHERE dj.idJourneeCaisse 
IN (SELECT jcp.id FROM JourneeCaisses jcp, JourneeCaisses jc
    WHERE jc.journee_precedente_id=jcp.id AND jcp.date_ouv>='$debut' AND jcp.date_ferm<='$fin' AND ( jc.date_ferm>'$fin')
    UNION
    SELECT jcp.journee_precedente_id FROM JourneeCaisses jcp, JourneeCaisses jc
    WHERE jc.journee_precedente_id=jcp.id AND jcp.date_ouv>='$debut' AND jcp.date_ferm<='$fin' AND  jc.date_ferm IS NULL
UNION
SELECT jcp.id FROM JourneeCaisses jcp
WHERE jcp.date_ferm <= '$fin' AND (NOT EXISTS (SELECT * FROM JourneeCaisses j WHERE j.journee_precedente_id=jcp.id)))
AND dj.devise_id = d.id
GROUP BY dj.devise_id) AS stock,

(SELECT SUM(dm.nombre * dm.taux) FROM DeviseMouvements dm
WHERE dm.devise_journee_id IN
(SELECT dj.id FROM DeviseJournees dj
WHERE dj.idJourneeCaisse 
IN (SELECT jc.id FROM JourneeCaisses jc
WHERE jc.date_ouv >= '$debut' AND jc.date_ferm <= '$fin' AND dj.devise_id = d.id)) AND dm.devise_intercaisse_id IS NULL
) AS cvd,

(SELECT SUM(dj.ecart_ouv) FROM DeviseJournees dj
WHERE dj.idJourneeCaisse 
IN (SELECT jc.id FROM JourneeCaisses jc
WHERE jc.date_ouv >= '$debut' AND jc.date_ferm <= '$fin')
 AND dj.devise_id = d.id
GROUP BY dj.devise_id) AS ecartOuv,
 
 (SELECT SUM(dj.ecart_ferm) FROM DeviseJournees dj
WHERE dj.idJourneeCaisse 
IN (SELECT jc.id FROM JourneeCaisses jc
WHERE jc.date_ouv >= '$debut' AND jc.date_ferm <= '$fin')
 AND dj.devise_id = d.id
GROUP BY dj.devise_id) AS ecartFerm
 
 FROM Devises d
";
        $req1 = "SELECT d.id as id,d.code as devise, 
(SELECT SUM(dj.qte_ouv)  FROM DeviseJournees dj 
 WHERE dj.idJourneeCaisse IN 
 (SELECT jc.id FROM JourneeCaisses jc, JourneeCaisses jcp
 WHERE jc.journee_precedente_id=jcp.id AND jcp.date_comptable<'$debut' AND jc.date_comptable >= '$debut' AND jc.date_comptable <= '$fin'
 UNION
SELECT jcp.id FROM JourneeCaisses jcp
WHERE jcp.date_comptable < '$debut' AND (NOT EXISTS (SELECT * FROM JourneeCaisses j WHERE j.journee_precedente_id=jcp.id)))
 AND dj.devise_id = d.id
 GROUP BY dj.devise_id
) AS qteOuv,

(SELECT SUM(dj.qte_achat) FROM DeviseJournees dj
WHERE dj.idJourneeCaisse 
IN (SELECT jc.id FROM JourneeCaisses jc
WHERE jc.date_comptable >= '$debut' AND jc.date_comptable <= '$fin' AND dj.devise_id = d.id)
GROUP BY dj.devise_id) AS achat,

 (SELECT SUM(dj.qte_vente) FROM DeviseJournees dj
WHERE dj.idJourneeCaisse 
IN (SELECT jc.id FROM JourneeCaisses jc
WHERE jc.date_comptable >= '$debut' AND jc.date_comptable <= '$fin' AND dj.devise_id = d.id)
GROUP BY dj.devise_id) AS vente,

(SELECT SUM(dj.qte_ferm) FROM DeviseJournees dj
WHERE dj.idJourneeCaisse 
IN (SELECT jcp.id FROM JourneeCaisses jcp, JourneeCaisses jc
    WHERE jc.journee_precedente_id=jcp.id AND jcp.date_comptable>='$debut' AND jcp.date_comptable<='$fin' AND ( jc.date_comptable>'$fin')
    UNION
    SELECT jcp.journee_precedente_id FROM JourneeCaisses jcp, JourneeCaisses jc
    WHERE jc.journee_precedente_id=jcp.id AND jcp.date_comptable>='$debut' AND jcp.date_comptable<='$fin' AND  jc.date_comptable IS NULL
UNION
SELECT jcp.id FROM JourneeCaisses jcp
WHERE jcp.date_comptable <= '$fin' AND (NOT EXISTS (SELECT * FROM JourneeCaisses j WHERE j.journee_precedente_id=jcp.id)))
AND dj.devise_id = d.id
GROUP BY dj.devise_id) AS stock,

(SELECT SUM(dm.nombre * dm.taux) FROM DeviseMouvements dm
WHERE dm.devise_journee_id IN
(SELECT dj.id FROM DeviseJournees dj
WHERE dj.idJourneeCaisse 
IN (SELECT jc.id FROM JourneeCaisses jc
WHERE jc.date_comptable >= '$debut' AND jc.date_comptable <= '$fin' AND dj.devise_id = d.id)) AND dm.devise_intercaisse_id IS NULL
) AS cvd,

(SELECT SUM(dj.ecart_ouv) FROM DeviseJournees dj
WHERE dj.idJourneeCaisse 
IN (SELECT jc.id FROM JourneeCaisses jc
WHERE jc.date_comptable >= '$debut' AND jc.date_comptable <= '$fin')
 AND dj.devise_id = d.id
GROUP BY dj.devise_id) AS ecartOuv,
 
 (SELECT SUM(dj.ecart_ferm) FROM DeviseJournees dj
WHERE dj.idJourneeCaisse 
IN (SELECT jc.id FROM JourneeCaisses jc
WHERE jc.date_comptable >= '$debut' AND jc.date_comptable <= '$fin')
 AND dj.devise_id = d.id
GROUP BY dj.devise_id) AS ecartFerm
 
 FROM Devises d
";

        try {
            $stmt = $em->getConnection()->prepare($req);
        } catch (DBALException $e) {
        }
        $stmt->execute([]);
        //dump($stmt);die();
        return $stmt->fetchAll();
    }

    public function trouverDevise1( \DateTime $date)
    {
        $dateDeb=new \DateTime('2018-12-01 00:00:00');
        $dateFin=new \DateTime('2018-12-01 00:00:00');
        $val = 100;
        $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('1'));
        $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('t'));
        $dateDeb = $dateDeb->format('Y/m/d');
        $dateFin = $dateFin->format('Y/m/d');
        //dump($dateFin);die();
        $em = $this->getEntityManager();
        $req="SELECT CAST(jc.date_ferm as date) as dt, dev.code as devise, SUM(d.qte_ouv) as ouverture, SUM(d.qte_ferm) as fermeture, dev.tx_vente as taux, d.qte_vente * tx_vente - d.qte_achat * tx_achat as VA, SUM(d.qte_vente * tx_vente - d.qte_achat * tx_achat) as cummul, d.qte_ferm * dev.tx_vente + SUM(d.qte_vente - d.qte_achat) as marge FROM DeviseJournees d, JourneeCaisses jc, Devises dev WHERE d.idJourneeCaisse=jc.id AND d.devise_id=dev.id AND date_ferm >= '$dateDeb' AND date_ferm <= '$dateFin' GROUP BY cast(date_ouv as date),devise ORDER BY CAST(date_ouv as date) ASC";
        try {

            $stmt = $em->getConnection()->prepare($req);
            $stmt->bindParam(1,$val, \PDO::PARAM_INT);
        } catch (DBALException $e) {
        }
        $stmt->execute([]);

        return $stmt->fetchAll();
    }

    public function trouverDevise( \DateTime $date)
    {
        $dateDeb=new \DateTime('2018-12-01 00:00:00');
        $dateFin=new \DateTime('2018-12-01 00:00:00');
        $val = 100;
        $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('1'));
        $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('t'));
        $dateDeb = $dateDeb->format('Y/m/d');
        $dateFin = $dateFin->format('Y/m/d');
        //dump($dateFin);die();
        $em = $this->getEntityManager();
        $req="SELECT CAST(jc.date_ferm as date) as dt, dev.code as devise, SUM(d.qte_ouv) as ouverture, SUM(d.qte_ferm) as fermeture, dev.tx_vente as taux, d.qte_vente * tx_vente - d.qte_achat * tx_achat as VA, SUM(d.qte_vente * tx_vente - d.qte_achat * tx_achat) as cummul, d.qte_ferm * dev.tx_vente + SUM(d.qte_vente - d.qte_achat) as marge FROM DeviseJournees d, JourneeCaisses jc, Devises dev WHERE d.idJourneeCaisse=jc.id AND d.devise_id=dev.id AND date_ferm >= '$dateDeb' AND date_ferm <= '$dateFin' GROUP BY cast(date_ouv as date),devise ORDER BY CAST(date_ouv as date) ASC";
        $req = "";
        try {

            $stmt = $em->getConnection()->prepare($req);
            $stmt->bindParam(1,$val, \PDO::PARAM_INT);
        } catch (DBALException $e) {
        }
        $stmt->execute([]);

        return $stmt->fetchAll();
    }


    public function getDeviseJourneePrec(DeviseJournees $deviseJournee)
    {
        $qb = $this->createQueryBuilder('d');
        //dump($deviseJournee->getJourneeCaisse()->getJourneePrecedente());die();
        try {
            return $qb
                ->innerJoin('d.devise', 'devise')
                ->innerJoin('d.journeeCaisse', 'jc')
                ->innerJoin('d.journeeCaisse', 'jcp')
                ->andWhere($qb->expr()->eq('jc', ':djjc'))
                ->andWhere($qb->expr()->eq('d.devise', ':dev'))
                ->setParameters(['djjc' => ($deviseJournee->getJourneeCaisse()->getJourneePrecedente()) ? $deviseJournee->getJourneeCaisse()->getJourneePrecedente()->getId() : 0, 'dev' => $deviseJournee->getDevise()->getId()])
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }
    }

    public function getDeviseOuv( \DateTime $dateDeb, \DateTime $dateFin, Devises $devise)
    {
        //$date = $date->format('Y/m/d');
        $debut = $dateDeb->format('Y/m/d');
        $fin = $dateFin->format('Y/m/d');
        $devise=$devise->getId();
        $em = $this->getEntityManager();
        $req="SELECT *  FROM DeviseJournees dj
     WHERE dj.idJourneeCaisse IN
     
     (SELECT jc.id FROM journeecaisses jc, journeecaisses jcp
     WHERE
      jc.journee_precedente_id=jcp.id AND jcp.date_comptable<'$fin'
      AND jc.date_comptable >= '$debut' AND jc.date_comptable <= '$fin'                                                                                   
     )
     AND dj.devise_id = '$devise'";
        try {
            $stmt = $em->getConnection()->prepare($req);
            //$stmt->bindParam(1,$val, \PDO::PARAM_INT);
        } catch (DBALException $e) {
        }
        $stmt->execute([]);
        //dump($stmt);die();
        return $stmt->fetchAll();
    }
    public function getDeviseAchatVente( \DateTime $dateDeb, \DateTime $dateFin, Devises $devise)
    {
        //$date = $date->format('Y/m/d');
        $debut = $dateDeb->format('Y/m/d');
        $fin = $dateFin->format('Y/m/d');
        $devise=$devise->getId();
        $em = $this->getEntityManager();
        $req="
SELECT * FROM DeviseJournees dj
    WHERE dj.idJourneeCaisse 
    IN (SELECT jc.id FROM JourneeCaisses jc
    WHERE jc.date_comptable >= '$debut' AND jc.date_comptable <= '$fin'
     AND dj.devise_id = '$devise'
    )";
        try {
            $stmt = $em->getConnection()->prepare($req);
            //$stmt->bindParam(1,$val, \PDO::PARAM_INT);
        } catch (DBALException $e) {
        }
        $stmt->execute([]);
        //dump($stmt);die();
        return $stmt->fetchAll();
    }
    public function getDeviseFerm( \DateTime $dateDeb, \DateTime $dateFin, Devises $devise)
    {
        //$date = $date->format('Y/m/d');
        $debut = $dateDeb->format('Y/m/d');
        $fin = $dateFin->format('Y/m/d');
        $devise=$devise->getId();
        $em = $this->getEntityManager();
        $req="
SELECT * FROM DeviseJournees dj
WHERE dj.idJourneeCaisse 
IN (SELECT jcp.id FROM JourneeCaisses jcp, JourneeCaisses jc
    WHERE jc.journee_precedente_id=jcp.id AND jcp.date_comptable>='$debut' AND jcp.date_comptable <='$fin' AND jc.date_comptable >'$fin')
 AND dj.devise_id = '$devise'
   ";
        try {
            $stmt = $em->getConnection()->prepare($req);
            //$stmt->bindParam(1,$val, \PDO::PARAM_INT);
        } catch (DBALException $e) {
        }
        $stmt->execute([]);
        //dump($stmt);die();
        return $stmt->fetchAll();
    }
    public function getDeviseCvd( \DateTime $dateDeb, \DateTime $dateFin, Devises $devise)
    {
        //$date = $date->format('Y/m/d');
        $debut = $dateDeb->format('Y/m/d');
        $fin = $dateFin->format('Y/m/d');
        $devise=$devise->getId();
        $em = $this->getEntityManager();
        $req="
SELECT * FROM DeviseMouvements dm
WHERE dm.devise_journee_id IN
(SELECT dj.id FROM DeviseJournees dj
WHERE dj.idJourneeCaisse 
IN (SELECT jc.id FROM JourneeCaisses jc
WHERE jc.date_comptable >= '$debut' AND jc.date_comptable <= '$fin' AND dj.devise_id = $devise)) AND dm.devise_intercaisse_id IS NULL
";
        try {
            $stmt = $em->getConnection()->prepare($req);
            //$stmt->bindParam(1,$val, \PDO::PARAM_INT);
        } catch (DBALException $e) {
        }
        $stmt->execute([]);
        //dump($stmt);die();
        return $stmt->fetchAll();
    }
}
