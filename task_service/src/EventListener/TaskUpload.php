<?php

namespace App\EventListener;

use App\Entity\Task;
use App\Service\UploadTaskService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use JsonException;

#[AsDoctrineListener(event: Events::postPersist, priority: 500, connection: 'default')]
class TaskUpload
{
    public function __construct(
        private readonly UploadTaskService $uploadTaskService
    ) {

    }

    /**
     * @throws JsonException
     */
    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Task) {
            return;
        }

        $this->uploadTaskService->uploadTask($entity);
    }
}