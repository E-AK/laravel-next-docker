<?php

namespace App\Service;

use App\Message\SendEmailMessage;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

readonly class MailService
{
    public function __construct(
        private MailerInterface $mailer,
    ) {

    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmail(SendEmailMessage $message): void
    {
        $email = (new Email())
            ->from('test@test.com')
            ->to($message->getEmail())
            ->subject('Task')
            ->text($message->getText());

        $this->mailer->send($email);
    }
}
