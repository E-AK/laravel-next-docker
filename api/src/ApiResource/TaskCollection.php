<?php

namespace App\ApiResource;

use App\Entity\Task;
use Symfony\Component\HttpFoundation\JsonResponse;

use Doctrine\Common\Collections\Collection;

class TaskCollection extends JsonResponse
{
    public function __construct(
        Collection $tasks,
        int $status = 200,
        array $headers = [],
        bool $json = false
    )
    {
        $data = [];

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