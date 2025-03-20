<?php

namespace App\ApiResource;

use App\Entity\Task;

trait TaskDataTrait
{
    protected function getTaskData(Task $task): array
    {
        return [
            'id' => $task->getId(),
            'user_id' => $task->getUserId(),
            'text' => $task->getText(),
            'status' => $task->getStatus(),
            'created_at' => $task->getCreatedAt(),
            'updated_at' => $task->getUpdatedAt(),
        ];
    }
}