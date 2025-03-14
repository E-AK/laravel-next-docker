<?php

namespace App\Controller\Api\Auth;

use App\ApiResource\TokenResource;
use App\Entity\User;
use App\DTO\SignInDTO;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class SignInController extends AbstractController
{
    #[Route('/api/auth/signin', name: 'api_login', methods: ['POST'])]
    public function execute(
        #[MapRequestPayload] SignInDTO $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $JWTManager
    ): TokenResource {
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $request->email]);

        if (is_null($user)) {
            throw $this->createNotFoundException('Пользователь не найден');
        }

        if (!$passwordHasher->isPasswordValid($user, $request->password)) {
            throw $this->createNotFoundException('Пользователь не найден');
        }

        $token = $JWTManager->create($user);

        return new TokenResource($token);
    }
}