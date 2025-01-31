<?php

namespace App\ApiResource;

use App\Entity\Task;
use App\Entity\Notification;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;

class NotificationResource extends JsonResponse
{
    /**
     * @param bool $json If the data is already a JSON string
     */
    public function __construct(
        Notification $notification,
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