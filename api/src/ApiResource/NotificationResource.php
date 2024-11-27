<?php

namespace App\ApiResource;

use App\Entity\Task;
use App\Entity\TaskNotification;
use Symfony\Component\HttpFoundation\JsonResponse;

class NotificationResource extends JsonResponse
{
    public function __construct(
        TaskNotification $notification,
        int              $status = 200,
        array            $headers = [],
        bool             $json = false
    )
    {
        parent::__construct(
            [
                'data' => [
                    'id' => $notification->getId(),
                    'task_id' => $notification->getTask()->getId(),
                    'datetime' => $notification->getDatetime()?->format('Y-m-d H:i:s'),
                ],
            ],
            $status,
            $headers,
            $json
        );
    }
}