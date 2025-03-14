<?php

namespace App\ApiResource;

use App\Entity\Task;
use Symfony\Component\HttpFoundation\JsonResponse;

class TaskCollection extends JsonResponse
{
    use TaskDataTrait;

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
            $data['data'][] = $this->getTaskData($task);
        }

        parent::__construct(
            $data,
            $status,
            $headers,
            $json
        );
    }
}