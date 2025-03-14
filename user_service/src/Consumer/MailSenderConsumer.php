<?php

namespace App\Consumer;

use App\Message\SendEmailMessage;
use App\Service\MailService;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class MailSenderConsumer
{
    public function __construct(
        private MailService $mailService,
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
        $this->mailService->sendEmail($message);
    }
}
