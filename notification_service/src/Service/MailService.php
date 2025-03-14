<?php

namespace App\Service;

use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

readonly class MailService
{
    public function __construct(
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager,
    ) {

    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmail(Notification $taskNotification): void
    {
        $to = $taskNotification->getEmail();

        $text = $taskNotification->getTask()->getText();

        $email = (new Email())
            ->from('test@test.com')
            ->to($to)
            ->subject('Task')
            ->text("Выполни таску!\nЗадача: $text");

        $this->mailer->send($email);

        $taskNotification->setSent(true);

        $this->entityManager->persist($taskNotification);
        $this->entityManager->flush();
    }
}
