<?php

namespace App\Consumer;

use App\Message\SendEmailMessage;
use App\Repository\NotificationRepository;
use App\Repository\TaskNotificationRepository;
use App\Service\MailService;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class MailSenderConsumer
{
    public function __construct(
        private MailService $mailService,
        private NotificationRepository $taskNotificationRepository
    ) {

    }

    /**
     * @param  SendEmailMessage  $message
     *
     * @return void
     * @throws TransportExceptionInterface
     */
    public function __invoke(SendEmailMessage $message): void
    {
        $notification = $this->taskNotificationRepository
            ->find($message->getNotification()->getId());

        if (is_null($notification)) {
            return;
        }

        $this->mailService->sendEmail($notification);
    }
}
