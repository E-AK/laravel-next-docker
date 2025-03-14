<?php

namespace App\Controller\Api\Task;

use App\ApiResource\TaskResource;
use App\Entity\Task;
use App\Service\TaskService;
use JsonException;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class DeleteController extends AbstractController
{
    public function __construct(
        private readonly TaskService $taskService,
        private readonly ProducerInterface $deleteTaskProducer,
    ) {

    }

    /**
     * @throws JsonException
     */
    #[Route('/api/tasks/delete/{id}', methods: ['DELETE'])]
    public function execute(Task $task): JsonResponse
    {
        $this->taskService->deleteTask($task);

        $this->deleteTaskProducer->publish(json_encode([
            'id' => $task->getId(),
        ], JSON_THROW_ON_ERROR));

        return new TaskResource($task);
    }
}