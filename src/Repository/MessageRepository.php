<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\Topic;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function findByTopicWithDetails(Topic $topic, int $page = 1, int $limit = 20): array
    {
        $offset = ($page - 1) * $limit;

        return $this->createQueryBuilder('m')
            ->leftJoin('m.author', 'a')
            ->leftJoin('m.files', 'f')
            ->addSelect('a', 'f')
            ->where('m.topic = :topic')
            ->setParameter('topic', $topic)
            ->orderBy('m.createdAt', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByUser(User $user, int $limit = 50): array
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.topic', 't')
            ->leftJoin('t.board', 'b')
            ->leftJoin('b.category', 'c')
            ->addSelect('t', 'b', 'c')
            ->where('m.author = :user')
            ->setParameter('user', $user)
            ->orderBy('m.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findRecentMessages(int $limit = 10): array
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.author', 'a')
            ->leftJoin('m.topic', 't')
            ->leftJoin('t.board', 'b')
            ->leftJoin('b.category', 'c')
            ->addSelect('a', 't', 'b', 'c')
            ->orderBy('m.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function searchMessages(string $query, int $limit = 50): array
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.author', 'a')
            ->leftJoin('m.topic', 't')
            ->addSelect('a', 't')
            ->where('m.content LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('m.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function countByTopic(Topic $topic): int
    {
        return $this->createQueryBuilder('m')
            ->select('COUNT(m.id)')
            ->where('m.topic = :topic')
            ->setParameter('topic', $topic)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findMessagesWithFiles(int $limit = 20): array
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.files', 'f')
            ->leftJoin('m.author', 'a')
            ->leftJoin('m.topic', 't')
            ->addSelect('f', 'a', 't')
            ->where('f.id IS NOT NULL')
            ->orderBy('m.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findEditedMessages(int $limit = 20): array
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.author', 'a')
            ->leftJoin('m.topic', 't')
            ->addSelect('a', 't')
            ->where('m.isEdited = true')
            ->orderBy('m.updatedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getMessageStats(int $days = 30): array
    {
        $since = new \DateTime('-' . $days . ' days');
        
        return $this->createQueryBuilder('m')
            ->select('DATE(m.createdAt) as date, COUNT(m.id) as count')
            ->where('m.createdAt >= :since')
            ->setParameter('since', $since)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findLastMessageByTopic(Topic $topic): ?Message
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.author', 'a')
            ->addSelect('a')
            ->where('m.topic = :topic')
            ->setParameter('topic', $topic)
            ->orderBy('m.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function deleteByUser(User $user): int
    {
        return $this->createQueryBuilder('m')
            ->delete()
            ->where('m.author = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->execute();
    }
}