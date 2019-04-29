<?php

namespace App\Repository;

use App\Entity\RecetteDepenseAgences;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RecetteDepenseAgences|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecetteDepenseAgences|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecetteDepenseAgences[]    findAll()
 * @method RecetteDepenseAgences[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecetteDepenseAgencesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RecetteDepenseAgences::class);
    }

}
