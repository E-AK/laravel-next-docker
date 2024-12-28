<?php

namespace App\Controller\Api\Notifications;

use App\Entity\Notification;
use App\Service\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class DeleteController extends AbstractController
{
    public function __construct(
        private readonly NotificationService $notificationService
    ) {

    }

    #[Route('/api/task/notification/{id}', methods: ['DELETE'])]
    public function execute(
        Notification $taskNotification
    ) {
        return $this->notificationService->deleteNotification($taskNotification);
    }
}