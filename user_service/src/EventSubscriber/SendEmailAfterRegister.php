<?php

namespace App\EventSubscriber;

use App\Entity\Task;
use App\Entity\User;
use App\Event\RegisterEvent;
use App\Message\SendEmailMessage;
use App\Service\UploadTaskService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use JsonException;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SendEmailAfterRegister  implements EventSubscriberInterface
{
    public function __construct(
        private MessageBusInterface $bus,
    ) {

    }

    /**
     * @throws JsonException
     */
    public function sendEmail(RegisterEvent $event): void
    {
        $user = $event->getUser();

        $message = new SendEmailMessage(
            $user->getEmail(),
            'You have been registered in the system'
        );
        $this->bus->dispatch($message);
    }

    public static function getSubscribedEvents()
    {
        return [
            'register' => 'sendEmail',
        ];
    }
}