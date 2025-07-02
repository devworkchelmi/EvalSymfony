<?php

<<<<<<< HEAD
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cet email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 50)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private ?bool $isBlocked = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Message::class, orphanRemoval: true)]
    private Collection $messages;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Topic::class)]
    private Collection $topics;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->topics = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->setRoleBasedOnEmail();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        $this->setRoleBasedOnEmail();
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
//
    }

    public function getIsBlocked(): ?bool
    {
        return $this->isBlocked;
    }

    public function setIsBlocked(bool $isBlocked): static
    {
        $this->isBlocked = $isBlocked;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setAuthor($this);
        }
        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            if ($message->getAuthor() === $this) {
                $message->setAuthor(null);
            }
        }
        return $this;
    }

    public function getTopics(): Collection
    {
        return $this->topics;
    }

    public function addTopic(Topic $topic): static
    {
        if (!$this->topics->contains($topic)) {
            $this->topics->add($topic);
            $topic->setAuthor($this);
        }
        return $this;
    }

    public function removeTopic(Topic $topic): static
    {
        if ($this->topics->removeElement($topic)) {
            if ($topic->getAuthor() === $this) {
                $topic->setAuthor(null);
            }
        }
        return $this;
    }

    private function setRoleBasedOnEmail(): void
    {
        if ($this->email === null) {
            return;
        }

        if (str_ends_with($this->email, '@insider.fr')) {
            $this->roles = ['ROLE_INSIDER'];
        } elseif (str_ends_with($this->email, '@collaborator.fr')) {
            $this->roles = ['ROLE_COLLABORATION'];
        } elseif (str_ends_with($this->email, '@external.fr')) {
            $this->roles = ['ROLE_EXTERNE'];
        } else {
            $this->roles = ['ROLE_USER'];
        }
    }

    public function getMainRole(): string
    {
        $roles = $this->getRoles();
        
        if (in_array('ROLE_ADMIN', $roles)) {
            return 'Admin';
        } elseif (in_array('ROLE_INSIDER', $roles)) {
            return 'Insider';
        } elseif (in_array('ROLE_COLLABORATION', $roles)) {
            return 'Collaboration';
        } elseif (in_array('ROLE_EXTERNE', $roles)) {
            return 'Externe';
        }
        
        return 'Utilisateur';
    }

    public function isAdmin(): bool
    {
        return in_array('ROLE_ADMIN', $this->getRoles());
    }
}
=======
namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

<<<<<<< HEAD
/**
 * @extends ServiceEntityRepository<User>
 */
=======
>>>>>>> 78c486ae9d339e6df893c8a8326abd3c5bcc4104
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

<<<<<<< HEAD
    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
=======
>>>>>>> 78c486ae9d339e6df893c8a8326abd3c5bcc4104
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

<<<<<<< HEAD
    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
=======
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
>>>>>>> 78c486ae9d339e6df893c8a8326abd3c5bcc4104
>>>>>>> 95cb2b9e4c384f64e798e3109940de957a6d0251
