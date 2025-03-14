<?php

namespace App\Repository;

use App\Entity\Notification;
use DateTime;
use DateTimeZone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    /**
     * @throws \Exception
     */
    public function findNoSent()
    {
        $timezone = new DateTimeZone('Europe/Samara');
        $currentDateTime = new DateTime(timezone: $timezone);


        echo $currentDateTime->format('Y-m-d H:i:s') . "\n";

        return $this->createQueryBuilder('t')
            ->andWhere('t.sent = :val')
            ->setParameter('val', false)
            ->andWhere('t.datetime <= :currentDateTime')
            ->setParameter('currentDateTime', $currentDateTime)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return TaskNotification[] Returns an array of TaskNotification objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TaskNotification
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
