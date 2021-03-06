<?php

namespace App\Repository;

use App\Entity\Intercaisses;
use App\Entity\JourneeCaisses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Intercaisses|null find($id, $lockMode = null, $lockVersion = null)
 * @method Intercaisses|null findOneBy(array $criteria, array $orderBy = null)
 * @method Intercaisses[]    findAll()
 * @method Intercaisses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntercaissesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Intercaisses::class);
    }

    public function findMyIntercaisses(JourneeCaisses $journeeCaisse)
    {
        $qb = $this->createQueryBuilder('i');

        // On fait une jointure
        return $qb->select('i')
            //->addSelect('IDENTITY (i.journeeCaisseSortant)')
            //->addSelect('IDENTITY (i.journeeCaisseEntrant)')
            //->addSelect('i.mIntercaisse')
            ->where('i.journeeCaisseSortant=:journeeCaisse OR i.journeeCaisseEntrant=:journeeCaisse')
            ->setParameters(['journeeCaisse'=>$journeeCaisse])
            //->addOrderBy('i.statut', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findIntercaissesValides(JourneeCaisses $journeeCaisse, $entrant=true)
    {
        $qb = $this->createQueryBuilder('i');

           ($entrant)?$qb->where('i.journeeCaisseEntrant=:journeeCaisse')
               :$qb->where('i.journeeCaisseSortant=:journeeCaisse');

        return $qb->andWhere('i.statut= :valide OR i.statut= :autovalide')
            ->setParameters(['journeeCaisse'=>$journeeCaisse, 'valide'=>Intercaisses::VALIDE, 'autovalide'=>Intercaisses::VALIDATION_AUTO])
            ->addOrderBy('i.statut', 'ASC')
            ->getQuery()
            ->getResult();
    }
    
}
