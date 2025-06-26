<?php

namespace App\Repository;

use App\Entity\File;
use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, File::class);
    }

    public function findByMessage(Message $message): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.message = :message')
            ->setParameter('message', $message)
            ->orderBy('f.originalName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByMimeType(string $mimeType): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.mimeType LIKE :mimeType')
            ->setParameter('mimeType', $mimeType . '%')
            ->orderBy('f.originalName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getTotalSizeByUser(int $userId): int
    {
        return $this->createQueryBuilder('f')
            ->select('SUM(f.size)')
            ->leftJoin('f.message', 'm')
            ->leftJoin('m.author', 'a')
            ->where('a.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleScalarResult() ?? 0;
    }

    public function findOrphanFiles(): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.message IS NULL')
            ->getQuery()
            ->getResult();
    }

    public function deleteByPath(string $path): int
    {
        return $this->createQueryBuilder('f')
            ->delete()
            ->where('f.path = :path')
            ->setParameter('path', $path)
            ->getQuery()
            ->execute();
    }
}