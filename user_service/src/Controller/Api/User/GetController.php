<?php

namespace App\Controller\Api\User;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class GetController extends AbstractController
{
    #[Route('/api/user/me', methods: ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'])]
    /**
     * @param User $user
     */
    public function execute(
        #[CurrentUser]
        UserInterface $user
    ): JsonResponse {
        return new JsonResponse(
            data: [
                'data' => [
                    'email' => $user->getEmail(),
                ]
            ],
            headers: [
                'X-User-ID' => $user->getId(),
                'X-User-Email' => $user->getEmail(),
            ]
        );
    }
}