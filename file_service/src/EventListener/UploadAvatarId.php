<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\File;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use JsonException;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Uid\Uuid;

#[AsDoctrineListener(event: Events::postPersist, priority: 500, connection: 'default')]
readonly class UploadAvatarId
{
    public function __construct(
        private RequestStack $requestStack,
        private ProducerInterface $uploadAvatarProducer,
    ) {

    }

    /**
     * @throws JsonException
     */
    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof File) {
            return;
        }

        $avatar = $this->requestStack
            ->getCurrentRequest()
            ?->get('avatar');

        if (!$avatar) {
            return;
        }

        $this->uploadAvatarProducer->publish(json_encode([
            'file_id' => $entity->getId(),
            'user_id' => $entity->getUserId(),
        ], JSON_THROW_ON_ERROR));
    }
}
