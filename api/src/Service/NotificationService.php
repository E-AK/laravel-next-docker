<?php

namespace App\Service;

use App\ApiResource\NotificationResource;
use App\Entity\Task;
use App\Entity\TaskNotification;
use App\Message\SendEmailMessage;
use App\Model\NotificationDTO;
use App\Repository\TaskRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class NotificationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TaskRepository $taskRepository,
        private MessageBusInterface $bus,
    ) {

    }

    /**
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function createNotification(NotificationDTO $request): NotificationResource
    {
        $message = new SendEmailMessage();
        $this->bus->dispatch($message);

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