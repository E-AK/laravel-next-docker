<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\File;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<File>
 */
class FileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, File::class);
    }

    public function save(string $path): File
    {
        $file = new File();
        $file->setPath($path);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($file);
        $entityManager->flush();

        return $file;
    }
}
