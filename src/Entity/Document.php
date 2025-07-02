<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
<<<<<<< HEAD
use Doctrine\DBAL\Types\Types;
=======
>>>>>>> 95cb2b9e4c384f64e798e3109940de957a6d0251
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
<<<<<<< HEAD
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fileName = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }
=======
    private ?string $fileName = null;
    private ?int $id = null;
>>>>>>> 95cb2b9e4c384f64e798e3109940de957a6d0251

    public function getId(): ?int
    {
        return $this->id;
    }
<<<<<<< HEAD

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): static
    {
        $this->fileName = $fileName;
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
=======
>>>>>>> 95cb2b9e4c384f64e798e3109940de957a6d0251
}
