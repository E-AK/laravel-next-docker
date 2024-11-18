<?php

namespace App\Controller\Api\Auth;

use App\Entity\User;
use App\Model\SigninDto;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class SigninController extends AbstractController
{
    #[Route('/api/auth/signin', methods: ['POST'])]
    public function signin(
        #[MapRequestPayload] SigninDto $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $JWTManager
    ): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['login' => $request->login]);

        $error = [
            'message' => 'Неверные логин или пароль',
            'errors' => [
                'common' => 'Неверные логин или пароль',
            ]
        ];

        if (is_null($user)) {
            return new JsonResponse($error, 401);
        }

        if (!$passwordHasher->isPasswordValid($user, $request->password)) {
            return new JsonResponse($error, 401);
        }

        $token = $JWTManager->create($user);

        return new JsonResponse([
            'data' => ['token' => $token]
        ]);
    }
}