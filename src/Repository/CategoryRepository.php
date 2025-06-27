<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findByAllowedRole(string $role): array
    {
        return $this->createQueryBuilder('c')
            ->where('JSON_CONTAINS(c.allowedRoles, :role) = 1')
            ->setParameter('role', json_encode($role))
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllWithBoards(): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.boards', 'b')
            ->addSelect('b')
            ->orderBy('c.name', 'ASC')
            ->addOrderBy('b.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function canUserAccess(Category $category, array $userRoles): bool
    {
        $allowedRoles = $category->getAllowedRoles();
        
        if (empty($allowedRoles)) {
            return true;
        }

        return !empty(array_intersect($userRoles, $allowedRoles));
    }
}