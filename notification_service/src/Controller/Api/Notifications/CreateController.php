<?php

namespace App\Controller\Api\Notifications;

use App\DTO\NotificationDTO;
use App\Service\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class CreateController extends AbstractController
{
    public function __construct(
        private readonly NotificationService $notificationService
    ) {

    }

    #[Route('/api/notifications', methods: ['POST'])]
    public function execute(
        #[MapRequestPayload]
        NotificationDTO $notificationDTO,
        Request $request,
    ) {
        $email = $request->headers->get('X-User-Email');

        if ($email === null) {
            throw new AuthenticationException(Response::HTTP_UNAUTHORIZED);
        }

        return $this->notificationService->createNotification($notificationDTO, $email);
    }

}