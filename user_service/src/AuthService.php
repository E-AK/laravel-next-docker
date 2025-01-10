<?php

namespace App;

use Grpc\Auth\AuthInterface;
use Grpc\Auth\Request;
use Grpc\Auth\Response;
use JsonException;
use Spiral\RoadRunner\GRPC;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

readonly class AuthService implements AuthInterface
{
    public function __construct(
        private JWTTokenManagerInterface $jwtManager,
    ) {

    }

    /**
     * @throws JsonException
     */
    public function auth(GRPC\ContextInterface $ctx, Request $in): Response
    {
        $decodedJwtToken = $this->jwtManager->parse($in->getToken());

        if (!$decodedJwtToken) {
            throw new AuthenticationException(
                json_encode(
                    $decodedJwtToken,
                    JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT
                )
            );
        }

        if (!isset($decodedJwtToken['id'])) {
            throw new AuthenticationException(
                json_encode(
                    $decodedJwtToken,
                    JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT
                )
            );
        }

        return $decodedJwtToken['id'];
    }
}