<?php

namespace App;

use App\Entity\User;
use App\Repository\UserRepository;
use Grpc\Auth\AuthInterface;
use Grpc\Auth\Request;
use Grpc\Auth\Response;
use Spiral\RoadRunner\GRPC;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

readonly class AuthService implements AuthInterface
{
    public function __construct(
        private JWTTokenManagerInterface $jwtManager,
        private UserRepository $userRepository
    ) {

    }

    public function auth(GRPC\ContextInterface $ctx, Request $in): Response
    {
        $decodedJwtToken = $this->jwtManager->parse($in->getToken());

        if (!$decodedJwtToken) {
            throw new AuthenticationException('Invalid jwt Token: ' . $in->getToken());
        }

        if (!isset($decodedJwtToken['username'])) {
            throw new AuthenticationException('Invalid jwt Token: ' . json_encode($decodedJwtToken, JSON_PRETTY_PRINT));
        }

//        throw new Authenticationexception($decodedJwtToken['username']);

        /**
         * @var User $user
         */
        $user = $this->userRepository->findOneBy(['email'=> $decodedJwtToken['username']]);

        return (new Response())->setId($user->getId()?->toString());
    }
}