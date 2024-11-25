<?php

namespace App\ApiResource;

use App\Entity\Task;
use Symfony\Component\HttpFoundation\JsonResponse;

class TaskResource extends JsonResponse
{
    public function __construct(
        Task $task,
        int $status = 200,
        array $headers = [],
        bool $json = false
    )
    {
        parent::__construct(
            [
                'data' => [
                    'id' => $task->getId(),
                    'text' => $task->getText(),
                    'status' => $task->getStatus(),
                ],
            ],
            $status,
            $headers,
            $json
        );
    }
}