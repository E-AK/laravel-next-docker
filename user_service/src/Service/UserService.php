<?php

namespace App\Service;

use App\ApiResource\TokenResource;
use App\DTO\SignUpDTO;
use App\Event\RegisterEvent;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class UserService
{
    public function __construct(
        private readonly JWTTokenManagerInterface $JWTTokenManager,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly UserRepository $userRepository
    ) {

    }

    public function register(SignUpDTO $request)
    {
        $user = $this->userRepository->register($request->email, $request->password);
        $token = $this->JWTTokenManager->create($user);

        $event = new RegisterEvent($user);
        $this->eventDispatcher->dispatch($event, RegisterEvent::NAME);

        return new TokenResource($token);
    }
}