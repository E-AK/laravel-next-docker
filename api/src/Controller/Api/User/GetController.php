<?php

namespace App\Controller\Api\User;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetController extends AbstractController
{
    #[Route('/api/user/me', methods: ['GET'])]
    public function me(): JsonResponse {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        if (is_null($user)) {
            return new JsonResponse([
                'message' => 'Пользователь не найден',
                'error' => [
                    'common' => 'Пользователь не найден',
                ]
            ], 403);
        }

        return new JsonResponse([
            'data' => [
                'login' => $user->getLogin(),
            ]
        ]);
    }
}