<?php

namespace App\Service;

use App\Entity\Task;
use JsonException;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class UploadTaskService
{
    public function __construct(
        private readonly ProducerInterface $uploadTaskProducer,
    )
    {
    }

    /**
     * @throws JsonException
     */
    public function uploadTask(Task $task): void
    {
        $this->uploadTaskProducer->publish(json_encode([
            'id' => $task->getId(),
            'text' => $task->getText(),
            'status' => $task->getStatus(),
            'user_id' => $task->getUserId(),
        ], JSON_THROW_ON_ERROR));
    }
}