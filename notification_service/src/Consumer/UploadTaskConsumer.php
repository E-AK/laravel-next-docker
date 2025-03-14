<?php

namespace App\Consumer;

use App\Entity\Task;
use Exception;
use JsonException;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

readonly class UploadTaskConsumer implements ConsumerInterface
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
        try {
            $data = json_decode($msg->getBody(), true, 512, JSON_THROW_ON_ERROR);

            $task = new Task();
            $task->setId(new Uuid($data['id']));
            $task->setText($data['text']);
            $task->setUserId(new Uuid($data['user_id']));

            $this->entityManager->persist($task);
            $this->entityManager->flush();
        } catch (Exception) {
            //
        }

    }
}