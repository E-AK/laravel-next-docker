<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\File;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<File>
 */
class FileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, File::class);
    }

    public function save(string $path, Uuid $userId): File
    {
        $file = new File();
        $file->setPath($path);
        $file->setUserId($userId);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($file);
        $entityManager->flush();

        return $file;
    }
}
