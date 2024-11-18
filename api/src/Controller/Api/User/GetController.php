<?php

namespace App\Controller\Api\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetController extends AbstractController
{
    #[Route('/api/user/{id}', methods: ['GET'])]
    public function signup(
        int $id
    ): JsonResponse {
        return new JsonResponse([
            'data' => []
        ]);
    }
}