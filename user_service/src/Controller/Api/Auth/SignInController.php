<?php

namespace App\Controller\Api\Auth;

use App\ApiResource\TokenResource;
use App\DTO\SignInDTO;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class SignInController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService
    ) {
    }

    #[Route('/api/auth/signin', name: 'api_login', methods: ['POST'])]
    public function execute(
        #[MapRequestPayload] SignInDTO $request,
    ): TokenResource {
        return $this->userService->login($request);
    }
}