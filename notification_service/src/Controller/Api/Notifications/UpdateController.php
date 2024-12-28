<?php

namespace App\Controller\Api\Notifications;

use App\Entity\Notification;
use App\Model\NotificationDTO;
use App\Service\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateController extends AbstractController
{
    public function __construct(
        private readonly NotificationService $notificationService
    ) {

    }

    #[Route('/api/task/notification/{id}', methods: ['PUT'])]
    public function execute(
        #[MapRequestPayload]
        NotificationDTO $request,
        Notification $task
    ) {
        return $this->notificationService->updateNotification($request, $task);
    }
}