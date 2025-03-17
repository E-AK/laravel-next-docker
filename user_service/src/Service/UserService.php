<?php

namespace App\Service;

use App\ApiResource\TokenResource;
use App\DTO\SignInDTO;
use App\DTO\SignUpDTO;
use App\Entity\User;
use App\Event\RegisterEvent;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class UserService
{
    public function __construct(
        private readonly JWTTokenManagerInterface $JWTTokenManager,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {

    }

    public function login(SignInDTO $request): TokenResource
    {
        $user = $this->userRepository->findByEmail($request->email);

        if (is_null($user)) {
            $this->notFoundUserException();
        }

        if (!$this->passwordHasher->isPasswordValid($user, $request->password)) {
            $this->notFoundUserException();
        }

        $token = $this->JWTTokenManager->create($user);

        return new TokenResource($token);
    }

    public function register(SignUpDTO $request): TokenResource
    {
        $user = $this->userRepository->register($request->email, $request->password);
        $token = $this->JWTTokenManager->create($user);

        $event = new RegisterEvent($user);
        $this->eventDispatcher->dispatch($event, RegisterEvent::NAME);

        return new TokenResource($token);
    }

    private function notFoundUserException()
    {
        throw new NotFoundHttpException('Пользователь не найден');
    }
}