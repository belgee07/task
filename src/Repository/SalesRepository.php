<?php

// src/Repository/SalesRepository.php
namespace App\Repository;

use App\Entity\Sales;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SalesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sales::class);
    }

    public function findSalesByMonth(string $month)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('DATE_FORMAT(s.date, `%Y-%m`) = :month')
            ->setParameter('month', $month)
            ->getQuery()
            ->getResult();
    }
}
