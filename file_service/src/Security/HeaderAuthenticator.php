<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class HeaderAuthenticator extends AbstractAuthenticator
    implements AuthenticationEntryPointInterface
{
    public function supports(Request $request): ?bool
    {
        return $request->headers->has('X-User-Id');
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $userId = $request->headers->get('X-User-Id');

        if (empty($userId)) {
            throw new AuthenticationException('X-User-Id header is empty');
        }

        return new SelfValidatingPassport(
            new UserBadge($userId, function ($identifier) {
                return new User($identifier);
            })
        );
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        string $firewallName
    ): ?Response {
        return null;
    }

    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    ): ?Response {
        return new Response('Authentication Failed: '.$exception->getMessage(), 401);
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new Response(
            'Authentication Required',
            Response::HTTP_UNAUTHORIZED,
            ['WWW-Authenticate' => 'X-User-Id']
        );
    }
}
