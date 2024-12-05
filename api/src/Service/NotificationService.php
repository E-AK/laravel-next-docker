<?php

namespace App\Service;

use App\ApiResource\NotificationResource;
use App\Entity\Task;
use App\Entity\TaskNotification;
use App\Model\NotificationDTO;
use App\Repository\TaskRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class NotificationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TaskRepository $taskRepository,
    ) {

    }

    /**
     * @throws \Exception
     */
    public function createNotification(NotificationDTO $request): NotificationResource
    {
        /**
         * @var Task $task
         */
        $task = $this->taskRepository->find($request->taskId);

        if (is_null($task)) {
            throw new NotFoundHttpException('Задача не найдена');
        }

        $notification = new TaskNotification();

        $notification->setDatetime($request->datetime);
        $notification->setTask($task);
        $notification->setSent(false);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return new NotificationResource($notification);
    }

    public function deleteNotification(TaskNotification $notification): NotificationResource
    {
        $this->entityManager->remove($notification);
        $this->entityManager->flush();
        return new NotificationResource($notification);
    }

    public function updateNotification(NotificationDTO $request, TaskNotification $notification): NotificationResource
    {
        $notification->setDatetime($request->datetime);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return new NotificationResource($notification);
    }
}