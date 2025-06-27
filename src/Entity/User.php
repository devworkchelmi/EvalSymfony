<?php

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