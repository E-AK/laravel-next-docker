<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\File;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Uid\Uuid;

#[AsDoctrineListener(event: Events::prePersist, priority: 500, connection: 'default')]
readonly class SetUserId
{
    public function __construct(
        private RequestStack $requestStack,
    ) {

    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof File) {
            return;
        }

        $userId = $this->requestStack
            ->getCurrentRequest()
            ?->headers
            ->get('X-User-Id');

        if ($userId === null) {
            return;
        }

        $entity->setUserId(Uuid::fromString($userId));
    }
}
