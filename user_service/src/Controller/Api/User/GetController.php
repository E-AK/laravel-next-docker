<?php

namespace App\Controller\Api\User;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetController extends AbstractController
{
    #[Route('/api/user/me', methods: ['GET'])]
    public function execute(): JsonResponse {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        return new JsonResponse([
            'data' => [
                'email' => $user->getEmail(),
            ]
        ]);
    }
}