<?php

namespace App\Controller\Api\Notifications;

use App\ApiResource\NotificationCollection;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class GetByTaskController extends AbstractController
{
    #[Route('/api/notifications/task/{task}', methods: ['GET'])]
    public function execute(
        Task $task,
    ) {
        return new NotificationCollection($task->getNotifications());
    }
}