<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * @return Task[] Returns an array of Task objects
     */
    public function findByUserId(Uuid $userId): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.user_id = :user_id')
            ->setParameter('user_id', $userId)
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
