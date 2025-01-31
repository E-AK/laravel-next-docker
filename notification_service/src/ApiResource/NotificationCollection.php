<?php

namespace App\ApiResource;

use App\Entity\Notification;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\JsonResponse;

class NotificationCollection extends JsonResponse
{
    /**
     * @param bool $json If the data is already a JSON string
     */
    public function __construct(
        Collection $notifications,
        int              $status = 200,
        array            $headers = [],
        bool             $json = false
    )
    {
        $data = [
            'data' => []
        ];

        foreach ($notifications as $notification) {
            $data['data'][] =  [
                'id' => $notification->getId(),
                'task_id' => $notification->getTask()->getId(),
                'datetime' => $notification->getDatetime()?->format('Y-m-d H:i:s'),
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