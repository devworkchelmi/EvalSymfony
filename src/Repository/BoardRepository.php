<?php

namespace App\Repository;

use App\Entity\Board;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BoardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Board::class);
    }

    public function findByCategoryWithTopics(Category $category): array
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.topics', 't')
            ->addSelect('t')
            ->where('b.category = :category')
            ->setParameter('category', $category)
            ->orderBy('b.name', 'ASC')
            ->addOrderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findWithRecentTopics(int $boardId, int $limit = 10): ?Board
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.topics', 't')
            ->leftJoin('t.author', 'a')
            ->addSelect('t', 'a')
            ->where('b.id = :id')
            ->setParameter('id', $boardId)
            ->orderBy('t.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function countTopics(Board $board): int
    {
        return $this->createQueryBuilder('b')
            ->select('COUNT(t.id)')
            ->leftJoin('b.topics', 't')
            ->where('b = :board')
            ->setParameter('board', $board)
            ->getQuery()
            ->getSingleScalarResult();
    }
}