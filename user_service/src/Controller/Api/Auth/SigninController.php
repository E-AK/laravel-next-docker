<?php

namespace App\Controller\Api\Auth;

use App\ApiResource\TokenResource;
use App\ApiResource\UserNotFoundResource;
use App\Entity\User;
use App\DTO\SignInDTO;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class SignInController extends AbstractController
{
    #[Route('/api/auth/signin', methods: ['POST'], name: 'api_login')]
    public function execute(
        #[MapRequestPayload] SignInDTO $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $JWTManager
    ): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $request->email]);

        if (is_null($user)) {
            return new UserNotFoundResource();
        }

        if (!$passwordHasher->isPasswordValid($user, $request->password)) {
            return new UserNotFoundResource();
        }

        $token = $JWTManager->create($user);

        return new TokenResource($token);
    }
}