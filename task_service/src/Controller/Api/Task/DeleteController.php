<?php

namespace App\Controller\Api\Task;

use App\ApiResource\TaskResource;
use App\Entity\Task;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DeleteController extends AbstractController
{
    public function __construct(
        private readonly TaskService $taskService,
    ) {

    }

    #[Route('/api/task/delete/{id}', methods: ['DELETE'])]
    public function execute(Task $task): JsonResponse
    {
        $this->denyAccessUnlessGranted('delete', $task);

        $this->taskService->deleteTask($task);

        return new TaskResource($task);
    }
}