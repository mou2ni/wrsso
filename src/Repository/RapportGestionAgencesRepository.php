<?php

namespace App\Repository;

use App\Entity\RapportGestionAgences;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Agences|null find($id, $lockMode = null, $lockVersion = null)
 * @method Agences|null findOneBy(array $criteria, array $orderBy = null)
 * @method Agences[]    findAll()
 * @method Agences[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RapportGestionAgencesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RapportGestionAgences::class);
    }

}
