<?php

namespace App\Controller\Api\Notifications;

use App\Entity\Notification;
use App\Service\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class DeleteController extends AbstractController
{
    /**
     * @param  NotificationService  $notificationService
     */
    public function __construct(
        private readonly NotificationService $notificationService
    ) {

    }

    #[Route('/api/notification/{id}', methods: ['DELETE'])]
    public function execute(
        Notification $taskNotification
    ) {
        return $this->notificationService->deleteNotification($taskNotification);
    }
}