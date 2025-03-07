<?php

namespace App\EventListener;

use App\Entity\User;
use App\Repository\UserRepository;
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
        private TokenStorageInterface $tokenStorage,
        private UserRepository $userRepository
    ) {

    }

    public function __invoke(ControllerEvent $event): void
    {
//        $authorization = $event->getRequest()->headers->get('Authorization');
//
//        if (!$authorization) {
//            throw new AuthenticationException('Missing Authorization header');
//        }
//
//        $client = new AuthClient('user_service:9001', [
//            'credentials' => ChannelCredentials::createInsecure(),
//            'update_metadata' => function($metaData) use ($authorization) {
//                $metaData['authorization'] = [str_replace('Bearer ', '', $authorization)];
//
//                return $metaData;
//            }
//        ]);
//
//        $request = (new Request())->setToken($authorization);
//
//        try {
//            /**
//             * @var Response $response
//             */
//            [$response, $status] = $client->auth($request)->wait();
//
//            if ($status->code !== STATUS_OK) {
//                throw new AuthenticationException($status->details);
//            }
//
//            $userId = $response->getId();
//            if (!$userId) {
//                throw new AuthenticationException('User not found');
//            }
//
//            /**
//             * @var User $user
//             */
//            $user = $this->userRepository->find($userId);
//
//            $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
//            $this->tokenStorage->setToken($token);
//        } catch (Exception $exception) {
//            echo $exception->getMessage();
//            exit;
//            throw new AuthenticationException($exception->getMessage());
//        }
    }
}