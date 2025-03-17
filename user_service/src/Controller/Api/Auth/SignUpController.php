<?php

namespace App\Controller\Api\Auth;

use App\DTO\SignUpDTO;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class SignUpController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService
    ) {

    }

    #[Route('/api/auth/signup', methods: ['POST'])]
    public function execute(
        #[MapRequestPayload] SignUpDTO $request,
    ): JsonResponse {
        return $this->userService->register($request);
    }
}