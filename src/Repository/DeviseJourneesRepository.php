<?php

namespace App\Repository;

use App\Entity\DeviseJournees;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

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
        $qb = $this->_em->createQuery("SELECT CAST(jc.date_ouv as date) as dt, dev.code as devise,
SUM(d.qte_ouv) as ouverture,
SUM(d.qte_ferm) as fermeture,
dev.tx_vente as taux,
d.qte_vente - d.qte_achat as VA,
  SUM(d.qte_vente - d.qte_achat) as cummul,
  d.qte_ferm * dev.tx_vente + SUM(d.qte_vente - d.qte_achat) as marge
FROM devisejournees d, journeecaisses jc, devises dev
WHERE d.idJourneeCaisse=jc.id AND d.devise_id=dev.id
GROUP BY cast(date_ouv as date),devise
ORDER BY CAST(date_ouv as date) ASC");
$qb->getDQL();
$qb->getResult();
        return $qb;
    }
/*
 SELECT CAST(jc.date_ouv as date) as dt,
dev.code as devise,
SUM(d.qte_ouv) as ouverture,
SUM(d.qte_ferm) as fermeture,
dev.tx_vente as taux,
d.qte_vente - d.qte_achat as VA,
  SUM(d.qte_vente - d.qte_achat) as cummul,
  d.qte_ferm * dev.tx_vente + SUM(d.qte_vente - d.qte_achat) as marge
FROM devisejournees d, journeecaisses jc, devises dev
WHERE d.idJourneeCaisse=jc.id AND d.devise_id=dev.id
GROUP BY cast(date_ouv as date),devise
ORDER BY CAST(date_ouv as date) ASC
 */

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
