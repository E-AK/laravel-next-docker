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

class UploadUserAfterRegister implements EventSubscriberInterface
{
    public function __construct(
        private readonly ProducerInterface $uploadUserProducer,
    ) {

    }

    /**
     * @throws JsonException
     */
    public function uploadUser(RegisterEvent $event): void
    {
        $user = $event->getUser();

        $this->uploadUserProducer->publish(json_encode([
            'id'    => $user->getId(),
            'email' => $user->getEmail(),
        ], JSON_THROW_ON_ERROR));
    }

    public static function getSubscribedEvents()
    {
        return [
            'register' => 'uploadUser',
        ];
    }
}