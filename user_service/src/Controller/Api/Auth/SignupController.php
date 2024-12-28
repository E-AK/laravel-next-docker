<?php

namespace App\Controller\Api\Auth;

use App\ApiResource\TokenResource;
use App\Entity\User;
use App\DTO\SignUpDTO;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class SignUpController extends AbstractController
{
    #[Route('/api/auth/signup', methods: ['POST'])]
    public function execute(
        #[MapRequestPayload] SignUpDTO $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $JWTManager
    ): JsonResponse {
        if ($request->password !== $request->repeat_password) {
            return new JsonResponse(
                [
                    'message' => 'Пароли не совпадают',
                    'errors' => [
                        'repeat_password' => 'Пароли не совпадают',
                    ]
                ],
                422
            );
        }

        $user = new User();
        $user->setEmail($request->email);

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $request->password
        );

        $user->setPassword($hashedPassword);
        $token = $JWTManager->create($user);

        $entityManager->persist($user);
        $entityManager->flush();

        return new TokenResource($token);
    }
}