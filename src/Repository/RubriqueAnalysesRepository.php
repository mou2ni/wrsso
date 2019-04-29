<?php

namespace App\Repository;

use App\Entity\RubriqueAnalyses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RubriqueAnalyses|null find($id, $lockMode = null, $lockVersion = null)
 * @method RubriqueAnalyses|null findOneBy(array $criteria, array $orderBy = null)
 * @method RubriqueAnalyses[]    findAll()
 * @method RubriqueAnalyses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RubriqueAnalysesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RubriqueAnalyses::class);
    }

}
