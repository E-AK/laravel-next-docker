<?php

namespace App\ApiResource;

use App\Entity\Task;
use App\Entity\TaskNotification;
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

        /**
         * @var Task $task
         */
        foreach ($tasks as $task) {
            $notifications = [];

            /**
             * @var TaskNotification $notification
             */
            foreach ($task->getNotifications() as $notification) {
                $notifications[] = [
                    'id' => $notification->getId(),
                    'taskId' => $task->getId(),
                    'datetime' => $notification->getDatetime()?->format('Y-m-d H:i:s'),
                ];
            }

            $data['data'][] = [
                'id' => $task->getId(),
                'text' => $task->getText(),
                'status' => $task->getStatus(),
                'notifications' => $notifications,
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