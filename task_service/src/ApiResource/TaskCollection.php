<?php

namespace App\ApiResource;

use App\Entity\Task;
use Symfony\Component\HttpFoundation\JsonResponse;

class TaskCollection extends JsonResponse
{
    public function __construct(
        array $tasks,
        int $status = 200,
        array $headers = [],
        bool $json = false
    )
    {
        $data = [];

        /**
         * @var Task $task
         */
        foreach ($tasks as $task) {
            $data['data'][] = [
                'id' => $task->getId(),
                'text' => $task->getText(),
                'status' => $task->getStatus(),
            ];
        }

        parent::__construct(
            $data,
            $status,
            $headers,
            $json
        );
    }
}