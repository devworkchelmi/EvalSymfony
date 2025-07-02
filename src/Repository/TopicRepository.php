<<<<<<< HEAD
<?php 
=======
<?php
>>>>>>> 95cb2b9e4c384f64e798e3109940de957a6d0251

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

<<<<<<< HEAD
    // Chargement d'un topic avec ses messages et auteurs
=======
    public function findByBoardWithAuthors(Board $board, int $page = 1, int $limit = 20): array
    {
        $offset = ($page - 1) * $limit;

        return $this->createQueryBuilder('t')
            ->leftJoin('t.author', 'a')
            ->addSelect('a')
            ->where('t.board = :board')
            ->setParameter('board', $board)
            ->orderBy('t.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

>>>>>>> 95cb2b9e4c384f64e798e3109940de957a6d0251
    public function findWithMessages(int $topicId): ?Topic
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.messages', 'm')
            ->leftJoin('m.author', 'ma')
<<<<<<< HEAD
            ->leftJoin('t.author', 'ta')
            ->addSelect('m', 'ma', 'ta')
=======
            ->leftJoin('m.files', 'f')
            ->leftJoin('t.author', 'ta')
            ->addSelect('m', 'ma', 'f', 'ta')
>>>>>>> 95cb2b9e4c384f64e798e3109940de957a6d0251
            ->where('t.id = :id')
            ->setParameter('id', $topicId)
            ->orderBy('m.createdAt', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }

<<<<<<< HEAD
    // Autres méthodes utiles (récents, par board, par user...)
=======
>>>>>>> 95cb2b9e4c384f64e798e3109940de957a6d0251
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
<<<<<<< HEAD
} 


=======

    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.board', 'b')
            ->leftJoin('b.category', 'c')
            ->addSelect('b', 'c')
            ->where('t.author = :user')
            ->setParameter('user', $user)
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function countMessages(Topic $topic): int
    {
        return $this->createQueryBuilder('t')
            ->select('COUNT(m.id)')
            ->leftJoin('t.messages', 'm')
            ->where('t = :topic')
            ->setParameter('topic', $topic)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
>>>>>>> 95cb2b9e4c384f64e798e3109940de957a6d0251
