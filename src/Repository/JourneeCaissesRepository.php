<?php

namespace App\Repository;

use App\Entity\Caisses;
use App\Entity\Comptes;
use App\Entity\JourneeCaisses;
use App\Entity\Utilisateurs;
use App\Utils\GenererCompta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
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
        $req->orderBy('jc.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $pag = new Paginator($req);

        return $pag;
    }

    public function getOpenBanqueQb()
    {
        $qb=$this->createQueryBuilder('jc');
        return $qb->addSelect('c')
            ->innerJoin('jc.caisse', 'c', 'WITH', 'jc.caisse= c.id')
            ->where('jc.statut=:statut')
            ->andWhere('c.typeCaisse=:banque or c.typeCaisse=:compense')
            ->andWhere('c.dispoGuichet=1')
            ->setParameter('banque',Caisses::BANQUE)
            ->setParameter('compense',Caisses::COMPENSE)
            ->setParameter('statut',JourneeCaisses::ENCOURS);
    }

    public function getOpenJourneeCaisseQb($dateComptable, $myJournee)
    {
        $qb=$this->createQueryBuilder('jc');
        $qb->addSelect('c')
            ->innerJoin('jc.caisse', 'c', 'WITH', 'jc.caisse= c.id')
            ->where('jc.statut=:statut')
            ->andWhere('c.typeCaisse!=:banque and c.typeCaisse!=:compense')
            ->setParameter('banque',Caisses::BANQUE)->setParameter('compense', Caisses::COMPENSE)
        ;

        if ($myJournee->getCaisse()->getTypeCaisse()<> Caisses::GUICHET){
            $qb->andWhere('jc.dateComptable=:dateComptable or c.typeCaisse!=:typeCaisse')
                ->setParameter('dateComptable',$dateComptable)
                ->setParameter('typeCaisse',Caisses::GUICHET)
                ->andWhere('c.dispoGuichet=1');
        }
        return $qb->andWhere('jc!=:myJournee')
            ->setParameter('statut',JourneeCaisses::ENCOURS)
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

    public function findOneJourneeCaisseOuvert($caisse)
    {
        $qb = $this->createQueryBuilder('j');

        return $qb
            -->where($qb->expr()->eq('j.caisse', ':caisse'))
                ->andWhere($qb->expr()->isNull('j.journeeSuivante'))
                ->andWhere($qb->expr()->eq('j.statut',':ouvert'))
                ->setParameters(['caisse'=>$caisse, 'ouvert'=>JourneeCaisses::OUVERT])
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
        ->addOrderBy('jc.dateComptable', 'DESC')
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
    }

    //** retourne le solde net Ouverture (sommes des netOuv)
    // des premieres journées caisses de toutes les caisses de la datecomptable */
    public function getOuvertureTresorerie1(\DateTime $date)
    {
        $qb=$this->createQueryBuilder('jc');
        return $qb->select('SUM(jc.mLiquiditeOuv) as liquidite','SUM(jc.mSoldeElectOuv) as solde','SUM(jc.mCreditDiversOuv) as credit',
            'SUM(jc.mDetteDiversOuv) as dette', 'SUM(jc.mLiquiditeOuv + jc.mSoldeElectOuv) as dispo',
            'SUM(jc.mLiquiditeOuv + jc.mSoldeElectOuv + jc.mCreditDiversOuv - jc.mDetteDiversOuv ) as Ouverture')
            ->innerJoin('jc.journeePrecedente', 'jcp', 'WITH', 'jc.journeePrecedente= jcp.id')
            ->where('jcp.dateComptable<:fin')
            ->andWhere('jc.dateComptable>=:debut' )
            ->andWhere('jcp.dateComptable<:fin')
            ->setParameter('debut',$date)
            ->setParameter('fin',$date)
            //->groupBy('jc.dateComptable')
            ->getQuery()
            ->getOneOrNullResult();

    }
//** retourne le solde net Ouverture (sommes des netOuv)
    // des premieres journées caisses de toutes les caisses de la datecomptable */
    public function getOuvertureTresorerie(\DateTime $dateDeb, \DateTime $dateFin)
    {
        $debut = $dateDeb->format('Y/m/d');
        $fin = $dateFin->format('Y/m/d');
        $em = $this->getEntityManager();
        //$req="SELECT SUM(jc.m_liquidite_ferm)  as liquidite,SUM(jc.m_solde_elect_ferm) as solde,SUM(jc.m_dette_divers_ferm) as dette,SUM(jc.m_credit_divers_ferm) as credit, SUM(jc.m_liquidite_ferm + jc.m_solde_elect_ferm) as dispo, SUM(jc.m_liquidite_ferm + jc.m_solde_elect_ferm + jc.m_credit_divers_ferm - jc.m_dette_divers_ferm ) as fermeture FROM JourneeCaisses jc WHERE id NOT IN (SELECT jcp.journee_precedente_id FROM JourneeCaisses jcp WHERE jcp.date_comptable='$date') AND jc.date_comptable='$date'";
        $req="SELECT SUM(jc.m_liquidite_ouv) as liquidite,SUM(jc.m_solde_elect_ouv) as solde,SUM(jc.m_credit_divers_ouv) as credit,
            SUM(jc.m_dette_divers_ouv) as dette, SUM(jc.m_liquidite_ouv + jc.m_solde_elect_ouv) as dispo,
            SUM(jc.m_liquidite_ouv + jc.m_solde_elect_ouv + jc.m_credit_divers_ouv - jc.m_dette_divers_ouv ) as Ouverture 
            FROM JourneeCaisses jc
           WHERE jc.id IN (
SELECT jc.id FROM JourneeCaisses jc, JourneeCaisses jcp
 WHERE jc.journee_precedente_id=jcp.id AND jcp.date_comptable<'$debut' AND jc.date_comptable >= '$debut' AND jc.date_comptable <= '$fin'
 UNION
SELECT jcp.id FROM JourneeCaisses jcp
WHERE jcp.date_comptable < '$debut' AND (NOT EXISTS (SELECT * FROM JourneeCaisses j WHERE j.journee_precedente_id=jcp.id)))";
        try {
            $stmt = $em->getConnection()->prepare($req);
            $stmt->bindParam(1,$val, \PDO::PARAM_INT);
        } catch (DBALException $e) {
        }
        $stmt->execute([]);

        return $stmt->fetch();
    }
    //////////details
    public function getDetailsTresorerie($etat,\DateTime $dateDeb, \DateTime $dateFin)
    {
        $debut = $dateDeb->format('Y/m/d');
        $fin = $dateFin->format('Y/m/d');
        $em = $this->getEntityManager();
        if ($etat == 'appro'){
            $qb=$this->createQueryBuilder('jc');
            return $qb
                ->select('i.mIntercaisse as appro','jc.dateComptable as date','jc.id as id','c.code as caissesource')
                ->innerJoin('jc.intercaisseEntrants', 'i')
                ->innerJoin('i.journeeCaisseSortant', 'jcs')
                ->innerJoin('jcs.caisse', 'c', 'WITH', ' c.typeCaisse = :type')
                ->where('jc.dateComptable>=:dateDeb')
                ->andWhere( 'jc.dateComptable<=:dateFin')
                ->setParameters(['dateDeb'=>$debut,'dateFin'=>$fin, 'type'=>Caisses::BANQUE])
                ->getQuery()
                ->getResult();
        }
        $reqOuv="SELECT jc.id as id, jc.date_comptable as date, jc.m_liquidite_ouv as liquidite,jc.m_solde_elect_ouv as solde,jc.m_credit_divers_ouv as credit,
            jc.m_dette_divers_ouv as dette, SUM(jc.m_liquidite_ouv + m_solde_elect_ouv) as dispo, SUM(jc.m_liquidite_ouv + m_solde_elect_ouv + jc.m_credit_divers_ouv - m_dette_divers_ouv) as ouverture
            FROM JourneeCaisses jc
           WHERE jc.id IN (
SELECT jc.id FROM JourneeCaisses jc, JourneeCaisses jcp
 WHERE jc.journee_precedente_id=jcp.id AND jcp.date_comptable<'$debut' AND jc.date_comptable >= '$debut' AND jc.date_comptable <= '$fin'
 UNION
SELECT jcp.id FROM JourneeCaisses jcp
WHERE jcp.date_comptable < '$debut' AND (NOT EXISTS (SELECT * FROM JourneeCaisses j WHERE j.journee_precedente_id=jcp.id)))
GROUP BY jc.id";
        $reqRecap = "SELECT jc.id as id, jc.date_comptable as date, jc.m_emission_trans as emission, jc.m_reception_trans as reception,
jc.m_depense as depense, jc.m_recette as recette, SUM( jc.m_ecart_ouv + jc.m_ecart_ferm) as ecart, SUM(
jc.m_emission_trans - jc.m_reception_trans)  as compense 
FROM JourneeCaisses jc
WHERE jc.date_comptable >= '$debut' AND jc.date_comptable <= '$fin'
GROUP BY jc.id
";
        $reqFerm = "SELECT jc.id as id, jc.date_comptable as date, jc.m_liquidite_ferm as liquidite,jc.m_solde_elect_ferm as solde,jc.m_dette_divers_ferm as dette,jc.m_credit_divers_ferm as credit, SUM(jc.m_liquidite_ferm + jc.m_solde_elect_ferm) as dispo, SUM(jc.m_liquidite_ferm + jc.m_solde_elect_ferm + jc.m_credit_divers_ferm - jc.m_dette_divers_ferm ) as fermeture 
FROM JourneeCaisses jc 
WHERE id IN (
    SELECT jcp.id FROM JourneeCaisses jcp, JourneeCaisses jc
    WHERE jc.journee_precedente_id=jcp.id AND jcp.date_comptable>='$debut' AND jcp.date_comptable<='$fin' AND ( jc.date_comptable>'$fin')
    UNION
    SELECT jcp.journee_precedente_id FROM JourneeCaisses jcp, JourneeCaisses jc
    WHERE jc.journee_precedente_id=jcp.id AND jcp.date_comptable>='$debut' AND jcp.date_comptable<='$fin' AND  jc.date_comptable IS NULL
UNION
SELECT jcp.id FROM JourneeCaisses jcp
WHERE jcp.date_comptable <= '$fin' AND (NOT EXISTS (SELECT * FROM JourneeCaisses j WHERE j.journee_precedente_id=jcp.id)))
GROUP BY jc.id";

        if ($etat=='ouv')
        $req =$reqOuv;
        elseif ($etat=='crd')
            $req=$reqRecap;
        elseif ($etat=='ferm')
            $req=$reqFerm;
        //dump($req);die();
        try {
            $stmt = $em->getConnection()->prepare($req);
            $stmt->bindParam(1,$val, \PDO::PARAM_INT);
        } catch (DBALException $e) {
        }
        $stmt->execute([]);

        return $stmt->fetchAll();
    }

    //** retourne les sommes des compenses, des recettes, des depenses et des ecarts des journees caisses
    // de la date comptable */
    public function getCompenseRecetteDepenseEcartTresorerie(\DateTime $dateDeb, \DateTime $dateFin)
    {
        $debut = $dateDeb->format('Y/m/d');
        $fin = $dateFin->format('Y/m/d');
        $em = $this->getEntityManager();
        //$req="SELECT SUM(jc.m_liquidite_ferm)  as liquidite,SUM(jc.m_solde_elect_ferm) as solde,SUM(jc.m_dette_divers_ferm) as dette,SUM(jc.m_credit_divers_ferm) as credit, SUM(jc.m_liquidite_ferm + jc.m_solde_elect_ferm) as dispo, SUM(jc.m_liquidite_ferm + jc.m_solde_elect_ferm + jc.m_credit_divers_ferm - jc.m_dette_divers_ferm ) as fermeture FROM JourneeCaisses jc WHERE id NOT IN (SELECT jcp.journee_precedente_id FROM JourneeCaisses jcp WHERE jcp.date_comptable='$date') AND jc.date_comptable='$date'";
        $req="SELECT SUM(jc.m_emission_trans) as emission, SUM( jc.m_reception_trans) as reception, SUM(
jc.m_depense) as depense, SUM( jc.m_recette) as recette, SUM( jc.m_ecart_ouv + jc.m_ecart_ferm) as ecart, SUM(
jc.m_emission_trans - jc.m_reception_trans)  as compense 
FROM JourneeCaisses jc
WHERE jc.date_comptable >= '$debut' AND jc.date_comptable <= '$fin'
";
        try {
            $stmt = $em->getConnection()->prepare($req);
            $stmt->bindParam(1,$val, \PDO::PARAM_INT);
        } catch (DBALException $e) {
        }
        $stmt->execute([]);
//dump($stmt->fetch());die();
        return $stmt->fetch();
    }
//** retourne les sommes des compenses, des recettes, des depenses et des ecarts des journees caisses
    // de la date comptable */
    public function getCompenseRecetteDepenseEcartTresorerie1(\DateTime $date)
    {
        $qb=$this->createQueryBuilder('jc');
        return $qb
            ->select('SUM(jc.mEmissionTrans) as emission','SUM(jc.mReceptionTrans) as reception','SUM(jc.mDepense) as depense',
                'SUM(jc.mRecette) as recette', 'SUM(jc.mEcartOuv + jc.mEcartFerm) as ecart',
                'SUM(jc.mEmissionTrans - jc.mReceptionTrans ) as compense')
            ->where('jc.dateComptable=:dateComptable' /*or c.typeCaisse!=:typeCaisse*/)
            ->setParameter('dateComptable',$date)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //** retourne la somme des Appro des journees caisses
    // de la date comptable */
    public function getApproTresorerie(\DateTime $dateDeb, \DateTime $dateFin)
    {
        $debut = $dateDeb->format('Y/m/d');
        $fin = $dateFin->format('Y/m/d');
        $qb=$this->createQueryBuilder('jc');
        return $qb
            ->select('SUM(i.mIntercaisse) as appro')
            ->innerJoin('jc.intercaisseEntrants', 'i')
            ->innerJoin('i.journeeCaisseSortant', 'jcs')
            ->innerJoin('jcs.caisse', 'c', 'WITH', ' c.typeCaisse = :type')
            ->where('jc.dateComptable>=:dateDeb')
            ->andWhere( 'jc.dateComptable<=:dateFin')
            ->setParameters(['dateDeb'=>$debut,'dateFin'=>$fin, 'type'=>Caisses::BANQUE])
            ->getQuery()
            ->getOneOrNullResult();
        }


    //** retourne le solde net Ouverture (sommes des netOuv)
    // des premieres journées caisses de toutes les caisses de la datecomptable */
    public function getFermetureTresorerie( \DateTime $dateDeb, \DateTime $dateFin)
    {
        $debut = $dateDeb->format('Y/m/d');
        $fin = $dateFin->format('Y/m/d');
        $em = $this->getEntityManager();
        //$req="SELECT SUM(jc.m_liquidite_ferm)  as liquidite,SUM(jc.m_solde_elect_ferm) as solde,SUM(jc.m_dette_divers_ferm) as dette,SUM(jc.m_credit_divers_ferm) as credit, SUM(jc.m_liquidite_ferm + jc.m_solde_elect_ferm) as dispo, SUM(jc.m_liquidite_ferm + jc.m_solde_elect_ferm + jc.m_credit_divers_ferm - jc.m_dette_divers_ferm ) as fermeture FROM JourneeCaisses jc WHERE id NOT IN (SELECT jcp.journee_precedente_id FROM JourneeCaisses jcp WHERE jcp.date_comptable='$date') AND jc.date_comptable='$date'";
        $req="SELECT SUM(jc.m_liquidite_ferm)  as liquidite,SUM(jc.m_solde_elect_ferm) as solde,SUM(jc.m_dette_divers_ferm) as dette,SUM(jc.m_credit_divers_ferm) as credit, SUM(jc.m_liquidite_ferm + jc.m_solde_elect_ferm) as dispo, SUM(jc.m_liquidite_ferm + jc.m_solde_elect_ferm + jc.m_credit_divers_ferm - jc.m_dette_divers_ferm ) as fermeture 
FROM JourneeCaisses jc 
WHERE id IN (
    SELECT jcp.id FROM JourneeCaisses jcp, JourneeCaisses jc
    WHERE jc.journee_precedente_id=jcp.id AND jcp.date_comptable>='$debut' AND jcp.date_comptable<='$fin' AND ( jc.date_comptable>'$fin')
    UNION
    SELECT jcp.journee_precedente_id FROM JourneeCaisses jcp, JourneeCaisses jc
    WHERE jc.journee_precedente_id=jcp.id AND jcp.date_comptable>='$debut' AND jcp.date_comptable<='$fin' AND  jc.date_comptable IS NULL
UNION
SELECT jcp.id FROM JourneeCaisses jcp
WHERE jcp.date_comptable <= '$fin' AND (NOT EXISTS (SELECT * FROM JourneeCaisses j WHERE j.journee_precedente_id=jcp.id)))";
        try {
            $stmt = $em->getConnection()->prepare($req);
            $stmt->bindParam(1,$val, \PDO::PARAM_INT);
        } catch (DBALException $e) {
        }
        $stmt->execute([]);

        return $stmt->fetch();
    }

    public function getFermetureTresorerieV1(\DateTime $date)
    {
        $qb=$this->createQueryBuilder('jc');
        return $qb
            ->select('SUM(jcp.mLiquiditeFerm) as liquidite','SUM(jcp.mSoldeElectFerm) as solde','SUM(jcp.mCreditDiversFerm) as credit',
                'SUM(jcp.mDetteDiversFerm) as dette', 'SUM(jcp.mLiquiditeFerm + jcp.mSoldeElectFerm) as dispo',
                'SUM(jcp.mLiquiditeFerm + jcp.mSoldeElectFerm + jcp.mCreditDiversFerm - jcp.mDetteDiversFerm ) as fermeture'
            )
            ->innerJoin('jc.journeePrecedente', 'jcp', 'WITH', 'jc.journeePrecedente= jcp.id')
            ->where('jcp.dateComptable=:dateComptable' /*or c.typeCaisse!=:typeCaisse*/)
            ->andWhere('jc.dateComptable>:dateComptable')
            ->setParameter('dateComptable',$date)
            ->groupBy('jcp.dateComptable')
            ->getQuery()
            ->getOneOrNullResult();
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
    }
/*
 * ->addSelect('IDENTITY(jc.caisse) as idCaisse, IDENTITY(jc.utilisateur) as idUtilisateur,IDENTITY(jc.journeePrecedente) as idJP, jc.id as id, c.code as caisse, u.nom as nom, u.prenom as prenom
             ,jc.dateOuv as dateOuv, jc.mLiquiditeOuv as mLiquiditeOuv, jc.mSoldeElectOuv as mSoldeElectOuv,jc.mEcartOuv as mEcartOuv, jc.mDetteDiversOuv as mDetteDiversOuv, jc.mCreditDiversOuv as mCreditDiversOuv
             ,jc.dateFerm as dateFerm , jc.mLiquiditeFerm as mLiquiditeFerm, jc.mSoldeElectFerm as mSoldeElectFerm, jc.mDetteDiversFerm as mDetteDiversFerm, jc.mCreditDiversFerm as mCreditDiversFerm, jc.mEcartFerm as mEcartFerm
             ,jc.mIntercaisses as mIntercaisses, jc.mEmissionTrans as mEmissionTrans, jc.mReceptionTrans as mReceptionTrans, jc.mCvd as mCvd, jc.mRetraitClient as mRetraitClient, jc.mDepotClient as mDepotClient
             ,jc.mRecette as mRecette, jc.mDepense as mDepense' )
 */
    public function findRecapitulatifJourneeCaisses($dateDebut=null, $dateFin=null, $caisse=null,$utilisateur=null, $offset=0,$limit = 20){
        $qb=$this->createQueryBuilder('jc')
            ->addSelect('IDENTITY(jc.caisse) as idCaisse, IDENTITY(jc.utilisateur) as idUtilisateur,IDENTITY(jc.journeePrecedente) as idJP, jc.id as id, c.code as caisse, u.nom as nom, u.prenom as prenom
             ,jc.dateOuv as dateOuv, (jc.mLiquiditeOuv + jc.mSoldeElectOuv -jc.mDetteDiversOuv+ jc.mCreditDiversOuv) as soldeNetOuv
             ,jc.dateFerm as dateFerm , (jc.mDetteDiversFerm -jc.mCreditDiversFerm) as mDetteCredit,(jc.mLiquiditeFerm+jc.mSoldeElectFerm) as disponibiliteFerm, (jc.mLiquiditeFerm+jc.mSoldeElectFerm-jc.mDetteDiversFerm +jc.mCreditDiversFerm) as soldeNetFerm, jc.mEcartOuv as mEcartOuv, jc.mEcartFerm as mEcartFerm
             ,jc.mIntercaisses as mIntercaisses, jc.mEmissionTrans as mEnvoi, jc.mReceptionTrans as mReception, jc.mCvd as mCvd, jc.mRetraitClient as mRetraitClient, jc.mDepotClient as mDepotClient
             ,jc.mRecette as mRecette, jc.mDepense as mDepense' )
            ->innerJoin('jc.caisse','c', 'WITH', 'jc.caisse=c.id')
            ->innerJoin('jc.utilisateur','u', 'WITH', 'jc.utilisateur=u.id')
            ->where('jc.statut<>:statut')->setParameter('statut', JourneeCaisses::INITIAL);
        if ($dateDebut) $qb->andWhere('jc.dateOuv >=:dateDebut')->setParameter('dateDebut',$dateDebut);
        if ($dateFin) $qb->andWhere('jc.dateOuv <=:dateFin')->setParameter('dateFin',$dateFin);
        if ($caisse) $qb->andWhere('jc.caisse =:caisse')->setParameter('caisse',$caisse);
        if ($utilisateur) $qb->andWhere('jc.utilisateur =:utilisateur')->setParameter('utilisateur',$utilisateur);

        $qb->orderBy('jc.dateOuv','DESC')->addOrderBy('c.code','ASC')->addOrderBy('u.nom','ASC')->addOrderBy('u.prenom', 'ASC')
            ->setFirstResult($offset)->setMaxResults($limit);

        $pag=new Paginator($qb);

        return $pag;
    }
    
    public function tableauCroiseEcart($annee=null){
        if(!$annee) {
            $now=new \DateTime();
            $annee=$now->format('Y');
            //$ceMois=$now->format('F');
        }
        $qb=$this->createQueryBuilder('jc');
        /*$qb->select('u.id, u.nom, u.prenom
            ,SUM(CASE WHEN jc.dateComptable>=:mois0Debut and jc.dateComptable<=:mois0Fin THEN jc.mEcartOuv + jc.mEcartFerm ELSE 0 END) as mEcartMois0
            ,SUM(CASE WHEN jc.dateComptable>=:mois1Debut and jc.dateComptable<=:mois1Fin THEN jc.mEcartOuv + jc.mEcartFerm ELSE 0 END) as mEcartMois1
            ,SUM(CASE WHEN jc.dateComptable>=:mois2Debut and jc.dateComptable<=:mois2Fin THEN jc.mEcartOuv + jc.mEcartFerm ELSE 0 END) as mEcartMois2
            ')*/
        $qb->select('u.id, u.nom, u.prenom
            ,SUM(CASE WHEN jc.dateComptable>=:janDebut and jc.dateComptable<=:janFin THEN jc.mEcartOuv + jc.mEcartFerm ELSE 0 END) as mEcartJan
            ,SUM(CASE WHEN jc.dateComptable>=:fevDebut and jc.dateComptable<=:fevFin THEN jc.mEcartOuv + jc.mEcartFerm ELSE 0 END) as mEcartFev
            ,SUM(CASE WHEN jc.dateComptable>=:marDebut and jc.dateComptable<=:marFin THEN jc.mEcartOuv + jc.mEcartFerm ELSE 0 END) as mEcartMar
            ,SUM(CASE WHEN jc.dateComptable>=:avrDebut and jc.dateComptable<=:avrFin THEN jc.mEcartOuv + jc.mEcartFerm ELSE 0 END) as mEcartAvr
            ,SUM(CASE WHEN jc.dateComptable>=:maiDebut and jc.dateComptable<=:maiFin THEN jc.mEcartOuv + jc.mEcartFerm ELSE 0 END) as mEcartMai
            ,SUM(CASE WHEN jc.dateComptable>=:juinDebut and jc.dateComptable<=:juinFin THEN jc.mEcartOuv + jc.mEcartFerm ELSE 0 END) as mEcartJuin
            ,SUM(CASE WHEN jc.dateComptable>=:juilDebut and jc.dateComptable<=:juilFin THEN jc.mEcartOuv + jc.mEcartFerm ELSE 0 END) as mEcartJuil
            ,SUM(CASE WHEN jc.dateComptable>=:aouDebut and jc.dateComptable<=:aouFin THEN jc.mEcartOuv + jc.mEcartFerm ELSE 0 END) as mEcartAou
            ,SUM(CASE WHEN jc.dateComptable>=:sepDebut and jc.dateComptable<=:sepFin THEN jc.mEcartOuv + jc.mEcartFerm ELSE 0 END) as mEcartSep
            ,SUM(CASE WHEN jc.dateComptable>=:octDebut and jc.dateComptable<=:octFin THEN jc.mEcartOuv + jc.mEcartFerm ELSE 0 END) as mEcartOct
            ,SUM(CASE WHEN jc.dateComptable>=:novDebut and jc.dateComptable<=:novFin THEN jc.mEcartOuv + jc.mEcartFerm ELSE 0 END) as mEcartNov
            ,SUM(CASE WHEN jc.dateComptable>=:decDebut and jc.dateComptable<=:decFin THEN jc.mEcartOuv + jc.mEcartFerm ELSE 0 END) as mEcartDec
            ')
                ->innerJoin('jc.utilisateur','u', 'WITH', 'jc.utilisateur=u.id')
                ->setParameter('janDebut',new \DateTime($annee.'-01-01 00:00:00'))
                ->setParameter('janFin',new \DateTime($annee.'-01-31 23:59:59'))
                ->setParameter('fevDebut',new \DateTime($annee.'-02-01 00:00:00'))
                ->setParameter('fevFin',new \DateTime($annee.'-02-29 23:59:59'))
                ->setParameter('marDebut',new \DateTime($annee.'-03-01 00:00:00'))
                ->setParameter('marFin',new \DateTime($annee.'-03-31 23:59:59'))
                ->setParameter('avrDebut',new \DateTime($annee.'-04-01 00:00:00'))
                ->setParameter('avrFin',new \DateTime($annee.'-04-30 23:59:59'))
                ->setParameter('maiDebut',new \DateTime($annee.'-05-01 00:00:00'))
                ->setParameter('maiFin',new \DateTime($annee.'-05-31 23:59:59'))
                ->setParameter('juinDebut',new \DateTime($annee.'-06-01 00:00:00'))
                ->setParameter('juinFin',new \DateTime($annee.'-06-30 23:59:59'))
                ->setParameter('juilDebut',new \DateTime($annee.'-07-01 00:00:00'))
                ->setParameter('juilFin',new \DateTime($annee.'-07-31 23:59:59'))
                ->setParameter('aouDebut',new \DateTime($annee.'-08-01 00:00:00'))
                ->setParameter('aouFin',new \DateTime($annee.'-08-31 23:59:59'))
                ->setParameter('sepDebut',new \DateTime($annee.'-09-01 00:00:00'))
                ->setParameter('sepFin',new \DateTime($annee.'-09-30 23:59:59'))
                ->setParameter('octDebut',new \DateTime($annee.'-10-01 00:00:00'))
                ->setParameter('octFin',new \DateTime($annee.'-10-31 23:59:59'))
                ->setParameter('novDebut',new \DateTime($annee.'-11-01 00:00:00'))
                ->setParameter('novFin',new \DateTime($annee.'-11-30 23:59:59'))
                ->setParameter('decDebut',new \DateTime($annee.'-12-01 00:00:00'))
                ->setParameter('decFin',new \DateTime($annee.'-12-31 23:59:59'))
           /* ->setParameter('mois0Debut',new \DateTime($annee.'-10-01 00:00:00'))
            ->setParameter('mois0Fin',new \DateTime($annee.'-10-31 23:59:59'))
            ->setParameter('mois1Debut',new \DateTime($annee.'-11-01 00:00:00'))
            ->setParameter('mois1fin',new \DateTime($annee.'-11-30 23:59:59'))
            ->setParameter('mois2Debut',new \DateTime($annee.'-12-01 00:00:00'))
            ->setParameter('mois2Fin',new \DateTime($annee.'-12-31 23:59:59'))*/
            ->where('u.estCaissier=1')
            ->andWhere('jc.statut=:statut')->setParameter('statut',JourneeCaisses::CLOSE)
            ;

        return $qb->addGroupBy('u.id')
            ->orderBy('u.nom, u.prenom', 'ASC')
            ->getQuery()->getResult();
    }
}
