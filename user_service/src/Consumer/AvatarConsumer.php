<?php

namespace App\Consumer;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Uid\Uuid;

class AvatarConsumer implements ConsumerInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {

    }

    /**
     * @throws JsonException
     */
    public function execute(AMQPMessage $msg)
    {
        $data = json_decode(
            $msg->getBody(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $userId = $data['user_id'];

        $user = $this->userRepository->find($userId);

        if ($user=== null) {
            return;
        }

        $user->setAvatarId(Uuid::fromString($data['file_id']));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}