<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findByRole(string $role): array
    {
        return $this->createQueryBuilder('u')
            ->where('JSON_CONTAINS(u.roles, :role) = 1')
            ->setParameter('role', json_encode($role))
            ->orderBy('u.username', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findActiveUsers(): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.isBlocked = false')
            ->orderBy('u.username', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findBlockedUsers(): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.isBlocked = true')
            ->orderBy('u.username', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function searchUsers(string $query): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.username LIKE :query OR u.email LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('u.username', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findMostActiveUsers(int $limit = 10): array
    {
        return $this->createQueryBuilder('u')
            ->select('u, COUNT(m.id) as messageCount')
            ->leftJoin('u.messages', 'm')
            ->where('u.isBlocked = false')
            ->groupBy('u.id')
            ->orderBy('messageCount', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findRecentUsers(int $days = 7): array
    {
        $since = new \DateTime('-' . $days . ' days');
        
        return $this->createQueryBuilder('u')
            ->where('u.createdAt >= :since')
            ->setParameter('since', $since)
            ->orderBy('u.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function countByRole(): array
    {
        $result = $this->createQueryBuilder('u')
            ->select('u.roles, COUNT(u.id) as count')
            ->groupBy('u.roles')
            ->getQuery()
            ->getResult();

        $counts = [
            'ROLE_INSIDER' => 0,
            'ROLE_COLLABORATION' => 0,
            'ROLE_EXTERNE' => 0,
            'ROLE_ADMIN' => 0,
            'ROLE_USER' => 0
        ];

        foreach ($result as $row) {
            $roles = $row['roles'];
            $count = $row['count'];
            
            foreach ($roles as $role) {
                if (isset($counts[$role])) {
                    $counts[$role] += $count;
                }
            }
        }

        return $counts;
    }

    public function findWithStats(int $userId): ?array
    {
        $user = $this->find($userId);
        if (!$user) {
            return null;
        }

        $stats = $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT m.id) as messageCount, COUNT(DISTINCT t.id) as topicCount')
            ->leftJoin('u.messages', 'm')
            ->leftJoin('u.topics', 't')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleResult();

        return [
            'user' => $user,
            'messageCount' => $stats['messageCount'],
            'topicCount' => $stats['topicCount']
        ];
    }
}