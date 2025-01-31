<?php

namespace App\Consumer;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Uid\Uuid;

readonly class UserConsumer implements ConsumerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {

    }

    /**
     * @throws JsonException
     */
    public function execute(AMQPMessage $msg): void
    {
        echo $msg->getBody();
        $data = json_decode($msg->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $user = new User();
        $user->setId(new Uuid($data['id']));
        $user->setEmail($data['email']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}