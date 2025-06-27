<?php 

namespace App\Repository;

use App\Entity\Topic;
use App\Entity\Board;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TopicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Topic::class);
    }

    // Chargement d'un topic avec ses messages et auteurs
    public function findWithMessages(int $topicId): ?Topic
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.messages', 'm')
            ->leftJoin('m.author', 'ma')
            ->leftJoin('t.author', 'ta')
            ->addSelect('m', 'ma', 'ta')
            ->where('t.id = :id')
            ->setParameter('id', $topicId)
            ->orderBy('m.createdAt', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    // Autres méthodes utiles (récents, par board, par user...)
    public function findRecentTopics(int $limit = 10): array
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.author', 'a')
            ->leftJoin('t.board', 'b')
            ->leftJoin('b.category', 'c')
            ->addSelect('a', 'b', 'c')
            ->orderBy('t.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
} 


