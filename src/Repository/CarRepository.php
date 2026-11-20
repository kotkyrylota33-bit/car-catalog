<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function search(?string $query): array
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.brand', 'b')
            ->addSelect('b')
            ->orderBy('c.year', 'DESC');

        if ($query) {
            $qb->andWhere('c.model LIKE :q OR b.name LIKE :q')
               ->setParameter('q', '%' . $query . '%');
        }

        return $qb->getQuery()->getResult();
    }
}