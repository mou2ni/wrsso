<?php

namespace App\Repository;

use App\Entity\DeviseJournees;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\ResultSetMapping;
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
        $req="SELECT CAST(jc.date_ferm as date) as dt,
dev.code as devise,
SUM(d.qte_ouv) as ouverture,
SUM(d.qte_ferm) as fermeture,
dev.tx_vente as taux,
d.qte_vente * tx_vente - d.qte_achat * tx_achat as VA,
  SUM(d.qte_vente * tx_vente - d.qte_achat * tx_achat) as cummul,
  d.qte_ferm * dev.tx_vente + SUM(d.qte_vente - d.qte_achat) as marge
FROM devisejournees d, journeecaisses jc, devises dev
WHERE d.idJourneeCaisse=jc.id AND d.devise_id=dev.id AND date_ferm >= '$dateDeb' AND date_ferm <= '$dateFin'
GROUP BY cast(date_ouv as date),devise
ORDER BY CAST(date_ouv as date) ASC";
        try {

            $stmt = $em->getConnection()->prepare($req);
            $stmt->bindParam(1,$val, \PDO::PARAM_INT);
            //dump($stmt);die();
            //$stmt->bindParam(2,$dateFin);
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
