<?php

namespace App\Repository;

use App\Entity\Caisses;
use App\Entity\JourneeCaisses;
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

    


}
