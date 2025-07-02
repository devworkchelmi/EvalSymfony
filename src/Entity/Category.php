<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Board::class, orphanRemoval: true)]
    private Collection $boards;

    #[ORM\Column(type: 'json')]
    private array $allowedRoles = [];

    public function __construct()
    {
        $this->boards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getBoards(): Collection
    {
        return $this->boards;
    }

    public function addBoard(Board $board): static
    {
        if (!$this->boards->contains($board)) {
            $this->boards->add($board);
            $board->setCategory($this);
        }
        return $this;
    }

    public function removeBoard(Board $board): static
    {
        if ($this->boards->removeElement($board)) {
            if ($board->getCategory() === $this) {
                $board->setCategory(null);
            }
        }
        return $this;
    }

    public function getAllowedRoles(): array
    {
        return $this->allowedRoles;
    }

    public function setAllowedRoles(array $allowedRoles): static
    {
        $this->allowedRoles = $allowedRoles;
        return $this;
    }
    public function getDescription(): array
    {
        return $this->description;
    }

    public function setDescription(array $description): static
    {
        $this->description = $description;
        return $this;
    }
}