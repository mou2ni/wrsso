<?php

namespace App\Repository;

use App\Entity\Caisses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Caisses|null find($id, $lockMode = null, $lockVersion = null)
 * @method Caisses|null findOneBy(array $criteria, array $orderBy = null)
 * @method Caisses[]    findAll()
 * @method Caisses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CaissesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Caisses::class);
    }

    public function getClosedCaisseQb()
    {
        return $this->createQueryBuilder('c')
            ->where('c.statut=:statut')
            ->andWhere('c.typeCaisse=:type')
            ->orderBy('c.code', 'ASC')
            ->setParameter('statut',Caisses::FERME)
            ->setParameter('type',Caisses::GUICHET);

    }

    public function findAllJoinCompteOperation(){
        $qb=$this->createQueryBuilder('c');
        return $qb->select('c.id, c.libelle, c.code, co.numCompte')
            ->innerJoin('c.compteOperation','co', 'WITH', 'c.compteOperation= co.id')
            ->getQuery()
            ->getResult()
        ;
    }

//$qb->innerJoin('u.Group', 'g', 'WITH', 'u.status = ?1', 'g.id')
}
