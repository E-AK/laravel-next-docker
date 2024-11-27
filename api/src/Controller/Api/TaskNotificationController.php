<?php

namespace App\Controller\Api;

use App\Entity\Task;
use App\Entity\TaskNotification;
use App\Model\NotificationDTO;
use App\Service\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class TaskNotificationController extends AbstractController
{
    public function __construct(
        private readonly NotificationService $notificationService
    ) {

    }

    #[Route('/api/notification', methods: ['POST'])]
    public function createNotification(
        #[MapRequestPayload]
        NotificationDTO $request,
    ) {
        return $this->notificationService->createNotification($request);
    }

    #[Route('/api/task/notification/{id}', methods: ['DELETE'])]
    public function deleteNotification(
        TaskNotification $taskNotification
    ) {
        return $this->notificationService->deleteNotification($taskNotification);
    }

    #[Route('/api/task/notification/{id}', methods: ['PUT'])]
    public function updateNotification(
        #[MapRequestPayload]
        NotificationDTO $request,
        TaskNotification $task
    ) {
        return $this->notificationService->updateNotification($request, $task);
    }
}