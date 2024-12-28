<?php

namespace App\Controller\Api\Tasks;

use App\Entity\Task;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class StatusController extends AbstractController
{
    public function __construct(
        private readonly TaskService $taskService,
    ) {

    }

    #[Route('/api/task/status/{id}', methods: ['PATCH'])]
    public function execute(Task $task): JsonResponse
    {
        return $this->taskService->nextStatus($task);
    }
}