<?php

namespace App\Repository;

use App\Entity\SystemTransfert;
use App\Entity\TransfertInternationaux;
use App\Entity\Zones;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * TransfertInternationauxRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class TransfertInternationauxRepository extends EntityRepository
{
    /*public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TransfertInternationaux::class);
    }*/

    public function etatTransfertMois( \DateTime $date)
    {
        $qb = $this->createQueryBuilder('t');

        return $qb
            //->where($qb->expr()->eq('t.id', ':id'))
            //->where($qb->expr()->eq('j.caisse', ':caisse'))
            //->andWhere($qb->expr()->isNull('j.journeeSuivante'))
            //->andWhere($qb->expr()->eq('j.statut',':ouvert'))
            //->setParameters(['caisse'=>$caisse,'ouvert'=>JourneeCaisses::INITIAL])
            ->groupBy('t.idPays')
            ->orderBy('t.id')
            ->orderBy('t.idPays')
            //->setParameters(['id'=>!null])
            ->getQuery()
            ->getResult();
    }
    /**
     * @return TransfertInternationaux[]|\Doctrine\ORM\QueryBuilder
     */
    public function trouverTransfert(\DateTime $date)
    {
        $dateDeb=new \DateTime('2019-01-01 00:00:00');
        $dateFin=new \DateTime('2019-01-01 23:59:59');
        $debut = $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('1'))->format('Y/m/d H:i:s');
        $fin = $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('t'))->format('Y/m/d H:i:s');
        $em = $this->getEntityManager();
        $req="SELECT z.code AS zone, z.id AS zoneId, SystemTransfert.id AS typeId, p.libelle as paysId,

(SELECT SUM(CASE WHEN t.sens=1 THEN t.m_transfert_ttc ELSE 0 END) FROM TransfertInternationaux t WHERE t.id_pays_id = p.id AND p.zone_id=z.id AND z.code=zone AND t.idSystemTransfert=typeId AND t.date_transfert >= '$debut' AND t.date_transfert <= '$fin' ) AS emis,

(SELECT SUM(CASE WHEN t.sens=2 THEN t.m_transfert_ttc ELSE 0 END) FROM TransfertInternationaux t WHERE t.id_pays_id = p.id AND p.zone_id=z.id AND z.code=zone AND t.idSystemTransfert=typeId  AND t.date_transfert >= '$debut' AND t.date_transfert <= '$fin' ) AS recus,

(SELECT COUNT(CASE WHEN t.sens=1 THEN t.m_transfert_ttc END) FROM TransfertInternationaux t WHERE t.id_pays_id = p.id AND p.zone_id=z.id AND z.code=zone AND t.idSystemTransfert=typeId AND t.date_transfert >= '$debut' AND t.date_transfert <= '$fin' ) AS nemis,

(SELECT COUNT(CASE WHEN t.sens=2 THEN t.m_transfert_ttc END) FROM TransfertInternationaux t WHERE t.id_pays_id = p.id AND p.zone_id=z.id AND z.code=zone AND t.idSystemTransfert=typeId AND t.date_transfert >= '$debut' AND t.date_transfert <= '$fin' ) AS nrecus

FROM zones z, SystemTransfert, Pays p
WHERE z.id=p.zone_id

GROUP BY SystemTransfert.id,z.id,p.id
ORDER BY SystemTransfert.id, z.ordre, p.ordre
";
        try {
            $stmt = $em->getConnection()->prepare($req);
        } catch (DBALException $e) {
        }
        //$stmt->bindParam(1, '2019/01/01');
        //$stmt->bindValue(2, '2019/01/31');
        //$stmt->bindParam(1,$dateDeb);
        //$stmt->bindParam(2,$dateFin);
        $stmt->execute([]);
        //dump($stmt);
        return $stmt->fetchAll();
    }

    /**
     * @return TransfertInternationaux[]|\Doctrine\ORM\QueryBuilder
     */
    public function trouverTransfert1(SystemTransfert $type,Zones $zone, \DateTime $date)
    {
        $dateDeb=new \DateTime('2019-01-01 00:00:00');
        $dateFin=new \DateTime('2019-01-01 23:59:59');
        $debut = $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('1'))->format('Y/m/d H:i:s');
        $fin = $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('t'))->format('Y/m/d H:i:s');
        $date = $date -> format('m/Y');
        //$dateFin = $date -> format('t/m/Y');
        return $this->createQueryBuilder('Transfert')
            ->Join('Transfert.idPays','pays')
            ->Join('pays.zone','z')
            ->Join('Transfert.idSystemTransfert','type')
            ->Join('Transfert.journeeCaisse','jc')
            ->addSelect(
            //'type.societe as Societe',
                'type.libelle as typeTransfert',
                'z.id as zone',
                'z.code as code',
                'pays.libelle as nomPays',
                'SUM(CASE Transfert.sens WHEN \'1\' THEN Transfert.mTransfertTTC ELSE 0 END ) as emis',
                'SUM(CASE Transfert.sens WHEN \'2\' THEN Transfert.mTransfertTTC ELSE 0 END ) as recus')
            //'COUNT(CASE Transfert.sens WHEN \'1\' THEN Transfert.mTransfertTTC ELSE 0 END ) as NEMIS',
            //'COUNT(CASE Transfert.sens WHEN \'0\' THEN Transfert.mTransfertTTC ELSE 0 END ) as NRECUS')
            ->addGroupBy('type','zone','pays.id')
            ->addOrderBy('zone')
            ->Where('jc.dateOuv >= :param1')
            ->andWhere('jc.dateOuv <= :param2')
            ->andWhere('z =:zone')
            ->andWhere('type =:type')
            ->setParameter('param1' ,$dateDeb)
            ->setParameter('param2' ,$dateFin)
            ->setParameter('zone' ,$zone)
            ->setParameter('type' ,$type)
            ->getQuery()->getResult();
    }
    /**
     * @return TransfertInternationaux[]|\Doctrine\ORM\QueryBuilder
     */
    public function trouverTransfertTypeZone(\DateTime $date)
    {
        $dateDeb=new \DateTime('2019-01-01 00:00:00');
        $dateFin=new \DateTime('2019-01-01 23:59:59');
        $debut = $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('1'))->format('Y/m/d H:i:s');
        $fin = $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('t'))->format('Y/m/d H:i:s');
        $em = $this->getEntityManager();
        $req = "SELECT z.code AS zone, z.id AS zoneId, SystemTransfert.id AS typeId, z.detail AS detail,

(SELECT SUM(CASE WHEN t.sens=1 THEN t.m_transfert_ttc ELSE 0 END) FROM TransfertInternationaux t, Pays p WHERE p.zone_id=z.id AND t.id_pays_id=p.id AND z.code=zone AND t.idSystemTransfert=typeId AND t.date_transfert >= '$debut' AND t.date_transfert <= '$fin' ) AS emis,

(SELECT SUM(CASE WHEN t.sens=2 THEN t.m_transfert_ttc ELSE 0 END) FROM TransfertInternationaux t , Pays p WHERE p.zone_id=z.id AND t.id_pays_id=p.id AND z.code=zone AND t.idSystemTransfert=typeId  AND t.date_transfert >= '$debut' AND t.date_transfert <= '$fin' ) AS recus,

(SELECT COUNT(CASE WHEN t.sens=1 THEN t.m_transfert_ttc END) FROM TransfertInternationaux t, Pays p WHERE p.zone_id=z.id AND t.id_pays_id=p.id AND z.code=zone AND t.idSystemTransfert=typeId AND t.date_transfert >= '$debut' AND t.date_transfert <= '$fin' ) AS nemis,

(SELECT COUNT(CASE WHEN t.sens=2 THEN t.m_transfert_ttc END) FROM TransfertInternationaux t, Pays p WHERE p.zone_id=z.id AND t.id_pays_id=p.id AND z.code=zone AND t.idSystemTransfert=typeId AND t.date_transfert >= '$debut' AND t.date_transfert <= '$fin' ) AS nrecus

FROM zones z, SystemTransfert

GROUP BY SystemTransfert.id,z.id
ORDER BY SystemTransfert.id, z.ordre
";
        try {
            $stmt = $em->getConnection()->prepare($req);
        } catch (DBALException $e) {
        }
        //$stmt->bindParam(1, '2019/01/01');
        //$stmt->bindValue(2, '2019/01/31');
        //$stmt->bindParam(1,$dateDeb);
        //$stmt->bindParam(2,$dateFin);
        $stmt->execute([]);
        return $stmt->fetchAll();
    }
    /**
     * @return TransfertInternationaux[]|\Doctrine\ORM\QueryBuilder
     */
    public function trouverTransfertTypeZone1(SystemTransfert $type,Zones $zone, \DateTime $date)
    {
        $dateDeb=new \DateTime('2018-12-01 00:00:00');
        $dateFin=new \DateTime('2018-12-01 00:00:00');
        $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('1'));
        $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('t'));
        $date = $date -> format('m/Y');
        //$dateFin = $date -> format('t/m/Y');
        $req = $this->createQueryBuilder('Transfert')
            ->Join('Transfert.idPays','pays')
            ->leftJoin('pays.zone','z')
            ->Join('Transfert.idSystemTransfert','type')
            ->Join('Transfert.journeeCaisse','jc')
            ->select(
            //'type.societe as Societe',
                'type.libelle as typeTransfert',
                'type.id as typeId',
                'z.code as zone',
                'z.id as zoneId',
                'z.ordre as ordre',
                'pays.libelle as nomPays',
                'SUM(CASE Transfert.sens WHEN \'1\' THEN Transfert.mTransfertTTC ELSE 0 END ) as emis',
                'SUM(CASE Transfert.sens WHEN \'2\' THEN Transfert.mTransfertTTC ELSE 0 END ) as recus',
                'COUNT(CASE Transfert.sens WHEN \'1\' THEN Transfert.mTransfertTTC ELSE 0 END ) as NEMIS',
                'COUNT(CASE Transfert.sens WHEN \'0\' THEN Transfert.mTransfertTTC ELSE 0 END ) as NRECUS')
            ->addGroupBy('type','zone')
            ->Where('jc.dateOuv >= :param1')
            ->andWhere('jc.dateOuv <= :param2')
            ->andWhere('pays.zone = :zone')
            ->andWhere('type.id = :type')
            ->setParameter('param1' ,$dateDeb)
            ->setParameter('param2' ,$dateFin)
            ->setParameter('zone' ,$zone)
            ->setParameter('type' ,$type)
            ->addOrderBy('ordre')
        ->getQuery()->getResult();
        //dump($req->getDQL());die();
        return $req;
    }
    /**
     * @return TransfertInternationaux[]|\Doctrine\ORM\QueryBuilder
     */
    public function trouverTransfertType(\DateTime $date)
    {
        $dateDeb=new \DateTime('2019-01-01 00:00:00');
        $dateFin=new \DateTime('2019-01-01 23:59:59');
        $debut = $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('1'))->format('Y/m/d H:i:s');
        $fin = $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('t'))->format('Y/m/d H:i:s');
        $date = $date -> format('m/Y');
        //$dateFin = $date -> format('t/m/Y');
        return $this->createQueryBuilder('Transfert')
            ->Join('Transfert.idPays','pays')
            ->Join('Transfert.idSystemTransfert','type')
            ->Join('type.banque','b')
            ->addSelect(
            //'type.societe as Societe',
                'type.libelle as typeTransfert',
                'type.id as typeId',
                'b.libelle as banque',
                'SUM(CASE WHEN Transfert.sens = :envoi THEN Transfert.mTransfertTTC ELSE 0 END ) as EMIS',
                'SUM(CASE WHEN Transfert.sens = :reception THEN Transfert.mTransfertTTC ELSE 0 END ) as RECUS',
                "COUNT(CASE WHEN Transfert.sens = :envoi THEN 1 ELSE :nul END ) as NEMIS",
                "COUNT(CASE WHEN Transfert.sens = :reception THEN 1 ELSE :nul END ) as NRECUS")
            //'COUNT(CASE Transfert.sens WHEN \'2\' THEN Transfert.mTransfertTTC ELSE 0 END ) as NRECUS')
            ->addGroupBy('type')
            ->Where('Transfert.dateTransfert >= :dateDeb')
            ->andWhere('Transfert.dateTransfert <= :dateFin')
            ->setParameter('dateDeb' ,$dateDeb)
            ->setParameter('dateFin' ,$dateFin)
            ->setParameter('envoi' ,TransfertInternationaux::ENVOI)
            ->setParameter('reception' ,TransfertInternationaux::RECEPTION)
            ->setParameter('nul' ,null)
            ->getQuery()->getResult();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function nombreTransfertType(\DateTime $date)
    {
        $dateDeb=new \DateTime('2019-01-01 00:00:00');
        $dateFin=new \DateTime('2019-01-01 23:59:59');
        $debut = $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('1'))->format('Y/m/d H:i:s');
        $fin = $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('t'))->format('Y/m/d H:i:s');
        $date = $date -> format('m/Y');
        //$dateFin = $date -> format('t/m/Y');
        return $this->createQueryBuilder('Transfert')
            ->Join('Transfert.idPays','pays')
            ->Join('Transfert.idSystemTransfert','type')
            ->Join('Transfert.journeeCaisse','jc')
            ->addSelect(
            //'type.societe as Societe',
                'type.libelle as typeTransfert',
                "COUNT(CASE WHEN Transfert.sens = :sens1 THEN :value ELSE :nul END ) as NEMIS",
                "COUNT(CASE WHEN Transfert.sens = :sens2 THEN :value ELSE :nul END ) as NRECUS")
                //'COUNT(CASE WHEN Transfert.sens = \'2\' THEN 1 ELSE \'NULL\' END ) as NRECUS')
            //'COUNT(CASE Transfert.sens WHEN \'2\' THEN Transfert.mTransfertTTC ELSE 0 END ) as NRECUS')
            ->addGroupBy('type')
            ->Where('jc.dateOuv >= :param1')
            ->andWhere('jc.dateOuv <= :param2')
            ->setParameter('param1' ,$dateDeb)
            ->setParameter('param2' ,$dateFin)
            ->setParameter('sens1' ,1)
            ->setParameter('sens2' ,2)
            ->setParameter('value' ,1)
            ->setParameter('nul' ,null)
            ->getQuery()->getResult();
    }


    /**
     * @param \DateTime|null $dateDebut
     * @param \DateTime|null $dateFin
     * @param mixed|null $systemTransfert
     * @param string (detail, caisse) $typeAffichage
     * @param string (sum, listing) $typeDonnees
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function qbTransferts(\DateTime $dateDebut=null, \DateTime $dateFin=null, $systemTransfert=null, $typeAffichage='detail', $typeDonnees='sum', $caisse=null, $sens=null, $journeeCaisse=null, $agence=null){

        $qb=$this->createQueryBuilder('ti');
        if ($typeDonnees=='sum')
            $qb->select('st.id, st.libelle,  SUM(CASE WHEN ti.sens=:envoi THEN ti.mTransfert ELSE 0 END) as mEnvoi
            , SUM(CASE WHEN ti.sens=:envoi THEN ti.mFraisHt ELSE 0 END) as mFrais
            , SUM(CASE WHEN ti.sens=:envoi THEN ti.mTva ELSE 0 END) as mTVA
            , SUM(CASE WHEN ti.sens=:envoi THEN ti.mAutresTaxes ELSE 0 END) as mAutresTaxes
            , SUM(CASE WHEN ti.sens=:reception THEN ti.mTransfertTTC ELSE 0 END) as mReception')
                ->setParameter('envoi', TransfertInternationaux::ENVOI)->setParameter('reception', TransfertInternationaux::RECEPTION);
        else $qb->select('ti.id, st.libelle as typeTransfert, ti.sens as sens, ti.mTransfert as mTransfert, ti.mFraisHt as mFraisHt, ti.mTva as mTva
        , ti.mAutresTaxes as mAutresTaxes, ti.mTransfertTTC as mTransfertTTC, ti.dateTransfert as dateTransfert');

        $qb->innerJoin('ti.idSystemTransfert','st');
        if ($typeDonnees!='sum'){
            $qb->innerJoin('ti.idPays','p')
                ->addSelect('p.libelle as pays');
        }

        if ($typeAffichage=='detail' or $typeAffichage=='caisse' or $typeDonnees!='sum'){
            $qb->leftJoin('ti.journeeCaisse','jc')
                ->leftJoin('jc.caisse','c')
                ->addSelect('c.libelle as caisseLibelle')
                ->addSelect('c.id as caisseId');

        }
        if ($typeAffichage=='agence'){
            $qb->leftJoin('ti.journeeCaisse','jc')
                ->leftJoin('jc.caisse','c')
                ->leftJoin('c.agence','a')
                ->addSelect('a.code as agenceCode')
                ->addSelect('a.id as agenceId');

        }
        if ($dateDebut){
            //$string_date=$dateDebut->format('Y-m-d');
            //$dateDebut= new \DateTime($string_date.' 00:00:00');
            $qb->where('ti.dateTransfert>=:dateDebut')->setParameter('dateDebut',$dateDebut);
        }
        if ($dateFin) {
            //$string_date=$dateFin->format('Y-m-d');
            //$dateFin= new \DateTime($string_date.' 23:59:59');
            $qb->andWhere('ti.dateTransfert<=:dateFin')->setParameter('dateFin',$dateFin);
        }
        if($systemTransfert){
            $qb->andWhere('ti.idSystemTransfert=:systemTransfert')->setParameter('systemTransfert',$systemTransfert);
        }
        if($caisse){
            $qb->andWhere('jc.caisse=:caisse')->setParameter('caisse',$caisse);
        }
        if($sens){
            $qb->andWhere('ti.sens=:sens')->setParameter('sens',$sens);
        }
        if($journeeCaisse){
            $qb->andWhere('ti.journeeCaisse=:journeeCaisse')->setParameter('journeeCaisse',$journeeCaisse);
        }
        if($agence){
            $qb->andWhere('c.agence=:agence')->setParameter('agence',$agence);
        }
        return $qb;
    }

    public function findCompense(\DateTime $dateDebut=null, \DateTime $dateFin=null, SystemTransfert $systemTransfert=null, $typeAffichage='detail'){

        $qb=$this->qbTransferts($dateDebut,$dateFin,$systemTransfert,$typeAffichage,'sum');

        if ($typeAffichage=='transfert' or $typeAffichage=='detail' or $typeAffichage==null) $qb->groupBy('st.id');
        if ($typeAffichage=='detail' or $typeAffichage=='caisse')  $qb->addGroupBy('c.id')->orderBy('c.code','ASC');
        if ($typeAffichage=='agence')  $qb->addGroupBy('a.id')->orderBy('a.code','ASC');

         return   $qb->addOrderBy('st.libelle')
            ->getQuery()->getResult();
    }

    public function findListingTransferts(\DateTime $dateDebut=null, \DateTime $dateFin=null, $systemTransfert=null, $caisse=null, $sens=null, $journeeCaisse=null, $agence=null){
        $qb=$this->qbTransferts($dateDebut,$dateFin,$systemTransfert,'','listing',$caisse,$sens,$journeeCaisse,$agence);
        return   $qb->orderBy('c.libelle', 'ASC')->addOrderBy('ti.dateTransfert','DESC')->addOrderBy('st.libelle', 'ASC')
            ->getQuery()->getResult();

    }

    public function findCompensations(\DateTime $dateDebut, \DateTime $dateFin, $caisse, $nonCompense=true){
        $qb=$this->createQueryBuilder('ti')
            ->select('st.id, st.libelle,  SUM(CASE WHEN ti.sens=:envoi THEN ti.mTransfertTTC ELSE 0 END) as mEnvoi
            , SUM(CASE WHEN ti.sens=:reception THEN ti.mTransfertTTC ELSE 0 END) as mReception')
            ->setParameter('envoi', TransfertInternationaux::ENVOI)->setParameter('reception', TransfertInternationaux::RECEPTION)
            ->innerJoin('ti.idSystemTransfert','st', 'WITH', 'ti.idSystemTransfert=st.id')
            ->innerJoin('st.banque','b', 'WITH', 'st.banque=b.id')
            ->where('ti.dateTransfert>=:dateDebut')->setParameter('dateDebut',$dateDebut)
            ->andWhere('ti.dateTransfert<=:dateFin')->setParameter('dateFin',$dateFin)
            ->andWhere('st.banque=:caisse')->setParameter('caisse',$caisse)
            ->groupBy('st.id');
        if($nonCompense) $qb->andWhere('ti.compense is null');

        return   $qb->orderBy('st.libelle')
            ->getQuery()->getResult();

    }

    public function updateCompense(\DateTime $dateDebut, \DateTime $dateFin, $compense){
        $qb=$this->createQueryBuilder('ti');
        $qb ->update()
            ->set('ti.compense',$qb->expr()->literal($compense))
            ->where('ti.dateTransfert>= ?1')->setParameter(1,$dateDebut)
            ->andWhere('ti.dateTransfert<= ?2')->setParameter(2,$dateFin);

        return $qb->getQuery()->execute();
    }

    public function getSumTransfert(\DateTime $dateDebut, \DateTime $dateFin){
        return $qb=$this->createQueryBuilder('ti')
            ->select('SUM (ti.mTransfertTTC) as mTotalTransfert')
            ->where('ti.dateTransfert>=:dateDebut')->setParameter('dateDebut',$dateDebut)
            ->andWhere('ti.dateTransfert<=:dateFin')->setParameter('dateFin',$dateFin)
            ->getQuery()->getOneOrNullResult();

    }

}
