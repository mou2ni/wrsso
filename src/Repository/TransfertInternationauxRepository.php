<?php

namespace App\Repository;

use App\Entity\TransfertInternationaux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
        $dateDeb=new \DateTime('2018-12-01 00:00:00');
        $dateFin=new \DateTime('2018-12-01 00:00:00');
        $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('1'));
        $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('t'));
        $date = $date -> format('m/Y');
        //$dateFin = $date -> format('t/m/Y');
        return $this->createQueryBuilder('Transfert')
            ->Join('Transfert.idPays','pays')
            ->Join('Transfert.idSystemTransfert','type')
            ->Join('Transfert.idJourneeCaisse','jc')
            ->addSelect(
            //'type.societe as Societe',
                'type.libelle as typeTransfert',
                'pays.zone as zone',
                'pays.libelle as nomPays',
                'SUM(CASE Transfert.sens WHEN \'1\' THEN Transfert.mTransfertTTC ELSE 0 END ) as EMIS',
                'SUM(CASE Transfert.sens WHEN \'2\' THEN Transfert.mTransfertTTC ELSE 0 END ) as RECUS')
            //'COUNT(CASE Transfert.sens WHEN \'1\' THEN Transfert.mTransfertTTC ELSE 0 END ) as NEMIS',
            //'COUNT(CASE Transfert.sens WHEN \'0\' THEN Transfert.mTransfertTTC ELSE 0 END ) as NRECUS')
            ->addGroupBy('type','zone','pays.id')
            ->Where('jc.dateOuv >= :param1')
            ->andWhere('jc.dateOuv <= :param2')
            ->setParameter('param1' ,$dateDeb)
            ->setParameter('param2' ,$dateFin)
            ->getQuery()->getResult();
    }
    /**
     * @return TransfertInternationaux[]|\Doctrine\ORM\QueryBuilder
     */
    public function trouverTransfertTypeZone(\DateTime $date)
    {
        $dateDeb=new \DateTime('2018-12-01 00:00:00');
        $dateFin=new \DateTime('2018-12-01 00:00:00');
        $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('1'));
        $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('t'));
        $date = $date -> format('m/Y');
        //$dateFin = $date -> format('t/m/Y');
        return $this->createQueryBuilder('Transfert')
            ->Join('Transfert.idPays','pays')
            ->Join('Transfert.idSystemTransfert','type')
            ->Join('Transfert.idJourneeCaisse','jc')
            ->addSelect(
            //'type.societe as Societe',
                'type.libelle as typeTransfert',
                'pays.zone as zone',
                'pays.libelle as nomPays',
                'SUM(CASE Transfert.sens WHEN \'1\' THEN Transfert.mTransfertTTC ELSE 0 END ) as EMIS',
                'SUM(CASE Transfert.sens WHEN \'2\' THEN Transfert.mTransfertTTC ELSE 0 END ) as RECUS',
                'COUNT(CASE Transfert.sens WHEN \'1\' THEN Transfert.mTransfertTTC ELSE 0 END ) as NEMIS',
                'COUNT(CASE Transfert.sens WHEN \'0\' THEN Transfert.mTransfertTTC ELSE 0 END ) as NRECUS')
            ->addGroupBy('type','zone')
            ->Where('jc.dateOuv >= :param1')
            ->andWhere('jc.dateOuv <= :param2')
            ->setParameter('param1' ,$dateDeb)
            ->setParameter('param2' ,$dateFin)
            ->getQuery()->getResult();
    }
    /**
     * @return TransfertInternationaux[]|\Doctrine\ORM\QueryBuilder
     */
    public function trouverTransfertType(\DateTime $date)
    {
        $dateDeb=new \DateTime('2018-12-01 00:00:00');
        $dateFin=new \DateTime('2018-12-01 00:00:00');
        $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('1'));
        $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('t'));
        $date = $date -> format('m/Y');
        //$dateFin = $date -> format('t/m/Y');
        return $this->createQueryBuilder('Transfert')
            ->Join('Transfert.idPays','pays')
            ->Join('Transfert.idSystemTransfert','type')
            ->Join('Transfert.idJourneeCaisse','jc')
            ->addSelect(
            //'type.societe as Societe',
                'type.libelle as typeTransfert',
                'SUM(CASE Transfert.sens WHEN \'1\' THEN Transfert.mTransfertTTC ELSE 0 END ) as EMIS',
                'SUM(CASE Transfert.sens WHEN \'2\' THEN Transfert.mTransfertTTC ELSE 0 END ) as RECUS',
                "COUNT(CASE WHEN Transfert.sens = :sens1 THEN :value ELSE :nul END ) as NEMIS",
                "COUNT(CASE WHEN Transfert.sens = :sens2 THEN :value ELSE :nul END ) as NRECUS")
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
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function nombreTransfertType(\DateTime $date)
    {
        $dateDeb=new \DateTime('2018-12-01 00:00:00');
        $dateFin=new \DateTime('2018-12-01 00:00:00');
        $dateDeb->setDate($date->format('Y'),$date->format('m'),$date->format('1'));
        $dateFin->setDate($date->format('Y'),$date->format('m'),$date->format('t'));
        $date = $date -> format('m/Y');
        //$dateFin = $date -> format('t/m/Y');
        return $this->createQueryBuilder('Transfert')
            ->Join('Transfert.idPays','pays')
            ->Join('Transfert.idSystemTransfert','type')
            ->Join('Transfert.idJourneeCaisse','jc')
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
//COUNT(CASE WHEN rsp_ind = 0 then 1 ELSE NULL END) as "New",
//COUNT(CASE WHEN Col1 = 'A' THEN 1 END) AS CountWithoutElse,
//COUNT(CASE WHEN Col1 = 'A' THEN 1 ELSE NULL END) AS CountWithElseNull,
//COUNT(CASE WHEN Col1 = 'A' THEN 1 ELSE 0 END) AS CountWithElseZero

}