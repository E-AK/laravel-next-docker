<?php

namespace App\ApiResource;

use App\Entity\Task;
use Symfony\Component\HttpFoundation\JsonResponse;

class TaskResource extends JsonResponse
{
    public function __construct(
        Task  $notification,
        int   $status = 200,
        array $headers = [],
        bool  $json = false
    )
    {
        parent::__construct(
            [
                'data' => [
                    'id' => $notification->getId(),
                    'text' => $notification->getText(),
                    'status' => $notification->getStatus(),
                ],
            ],
            $status,
            $headers,
            $json
        );
    }
}