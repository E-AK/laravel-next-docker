<?php

namespace App\EventListener;

use App\Entity\User;
use Exception;
use Grpc\Auth\AuthClient;
use Grpc\Auth\Request;
use Grpc\Auth\Response;
use Grpc\ChannelCredentials;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

use Symfony\Component\Uid\Uuid;

use const Grpc\STATUS_OK;

#[AsEventListener]
final readonly class Auth
{
    public function __construct(
        private TokenStorageInterface $tokenStorage
    ) {

    }

    public function __invoke(ControllerEvent $event): void
    {
        $authorization = $event->getRequest()->headers->get('Authorization');

        if (!$authorization) {
            throw new AuthenticationException('Missing Authorization header');
        }

        $client = new AuthClient('user_service:9001', [
            'credentials' => ChannelCredentials::createInsecure(),
        ]);

        $request = (new Request())->setToken($authorization);

        try {
            /**
             * @var Response $response
             */
            [$response, $status] = $client->auth($request)->wait();

            if ($status->code !== STATUS_OK) {
                throw new AuthenticationException('Invalid token');
            }

            $userId = $response->getId();
            if (!$userId) {
                throw new AuthenticationException('User not found');
            }

            $user = (new User())->setId(new Uuid($userId));

            $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
            $this->tokenStorage->setToken($token);
        } catch (Exception $exception) {
            throw new AuthenticationException($exception->getMessage());
        }
    }
}