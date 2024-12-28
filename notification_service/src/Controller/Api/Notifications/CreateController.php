<?php

namespace App\Controller\Api\Notifications;

use App\Model\NotificationDTO;
use App\Service\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateController extends AbstractController
{
    public function __construct(
        private readonly NotificationService $notificationService
    ) {

    }

    #[Route('/api/notification', methods: ['POST'])]
    public function execute(
        #[MapRequestPayload]
        NotificationDTO $request,
    ) {
        return $this->notificationService->createNotification($request);
    }
}