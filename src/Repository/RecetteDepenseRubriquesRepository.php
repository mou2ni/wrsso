<?php

namespace App\Repository;

use App\Entity\RecetteDepenseRubriques;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RecetteDepenseRubriques|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecetteDepenseRubriques|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecetteDepenseRubriques[]    findAll()
 * @method RecetteDepenseRubriques[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecetteDepenseRubriquesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RecetteDepenseRubriques::class);
    }

}
