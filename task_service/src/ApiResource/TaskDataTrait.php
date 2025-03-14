<?php

namespace App\ApiResource;

use App\Entity\Task;

trait TaskDataTrait
{
    protected function getTaskData(Task $task): array
    {
        return [
            'id' => $task->getId(),
            'text' => $task->getText(),
            'status' => $task->getStatus(),
        ];
    }
}