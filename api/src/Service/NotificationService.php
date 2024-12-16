<?php

namespace App\Service;

use App\ApiResource\NotificationResource;
use App\Entity\Task;
use App\Entity\TaskNotification;
use App\Message\SendEmailMessage;
use App\Model\NotificationDTO;
use App\Repository\TaskRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;

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
        $timeZone = new DateTimeZone('UTC');

        $now = new DateTime(timezone: $timeZone);

        if (new DateTime($request->datetime, $timeZone) < $now) {
            throw new RuntimeException(
                'Вы не можете отправить уведомление в прошлом ('
                . json_encode(new DateTime($request->datetime, $timeZone)) . ') ('
                . json_encode($now) . ')'
            );
        }

        /**
         * @var Task $task
         */
        $task = $this->taskRepository->find($request->taskId);

        if (is_null($task)) {
            throw new NotFoundHttpException('Задача не найдена');
        }

        $notification = new TaskNotification();

        $notification->setDatetime(new DateTime($request->datetime, $timeZone));
        $notification->setTask($task);
        $notification->setSent(false);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        $now = new DateTime(timezone: $timeZone);
        $delay = max(0, $notification->getDatetime()
                    ?->getTimestamp() - $now->getTimestamp()) * 1000;

        $message = new SendEmailMessage($notification);
        $this->bus->dispatch($message, [new DelayStamp($delay)]);

        return new NotificationResource($notification);
    }

    public function deleteNotification(TaskNotification $notification): NotificationResource
    {
        $this->entityManager->remove($notification);
        $this->entityManager->flush();
        return new NotificationResource($notification);
    }

    /**
     * @throws Exception
     */
    public function updateNotification(NotificationDTO $request, TaskNotification $notification): NotificationResource
    {
        $timeZone = new DateTimeZone('UTC');
        $notification->setDatetime(new DateTime($request->datetime, $timeZone));

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return new NotificationResource($notification);
    }
}