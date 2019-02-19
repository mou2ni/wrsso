<?php

namespace App\Repository;

use App\Entity\Comptes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Caisses|null find($id, $lockMode = null, $lockVersion = null)
 * @method Caisses|null findOneBy(array $criteria, array $orderBy = null)
 * @method Caisses[]    findAll()
 * @method Caisses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComptesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comptes::class);
    }

    public function liste($offset,$limit = 10, $classe=0)
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.numCompte', 'ASC');
        if ($classe) {
            $qb->where('c.numCompte like \''.$classe.'%\'');
        }
        $qb->setFirstResult($offset)
            ->setMaxResults($limit)
            //->getQuery()
            //->getResult()
            ;
        $pag = new Paginator($qb);

        return $pag;

    }

    public function findCompteGestions(){
        $qb=$this->createQueryBuilder('c');
        return $qb
            ->where('c.numCompte like \'6%\' or c.numCompte like \'7%\' ')
            ->orderBy('c.numCompte', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findCompteCharges(){
        $qb=$this->createQueryBuilder('c');
        return $qb
            ->where('c.numCompte like \'6%\'')
            ->orderBy('c.numCompte', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findCompteProduits(){
        $qb=$this->createQueryBuilder('c');
        return $qb
            ->where('c.numCompte like \'7%\'')
            ->orderBy('c.numCompte', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findCompteTiers(){
        return $this->getCompteTiersQb()
            ->getQuery()
            ->getResult();
    }

    public function getCompteTiersQb(){
        $qb=$this->createQueryBuilder('c');
        return $qb
            ->where('c.numCompte like \'4%\' ')
            //->andWhere('c.numCompte not like \'47%\' ')
            ->orderBy('c.numCompte', 'ASC');
    }



//$qb->innerJoin('u.Group', 'g', 'WITH', 'u.status = ?1', 'g.id')
}
