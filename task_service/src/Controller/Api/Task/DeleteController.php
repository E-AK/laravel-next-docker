<?php

namespace App\Controller\Api\Task;

use App\ApiResource\TaskResource;
use App\Entity\Task;
use App\Service\TaskService;
use JsonException;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

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
    #[Route('/api/task/delete/{id}', methods: ['DELETE'])]
    public function execute(Task $task): JsonResponse
    {
        $this->denyAccessUnlessGranted('delete', $task);

        $this->taskService->deleteTask($task);

        $this->deleteTaskProducer->publish(json_encode([
            'id' => $task->getId(),
        ], JSON_THROW_ON_ERROR));

        return new TaskResource($task);
    }
}