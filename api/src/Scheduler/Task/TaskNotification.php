<?php

namespace App\Scheduler\Task;

use App\Repository\TaskNotificationRepository;
use App\Service\MailService;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

readonly class TaskNotification
{
    public function __construct(
        private MailService $mailService,
        private TaskNotificationRepository $taskNotificationRepository,
    ) {

    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(): void
    {
        /**
         * @var Collection<\App\Entity\TaskNotification> $taskNotifications
         */
        $taskNotifications = $this->taskNotificationRepository->findNoSent();

        /**
         * @var \App\Entity\TaskNotification $taskNotification
         */
        foreach ($taskNotifications as $taskNotification) {
            $this->mailService->sendEmail($taskNotification);
        }
    }
}