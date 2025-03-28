<?php

namespace App\Repository;

use App\Entity\Department;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DepartmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);
    }

    public function findByDepartmentName(string $departmentName): ?Department
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.departmentName = :name') // department_name биш departmentName ашиглана
            ->setParameter('name', $departmentName)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
}
