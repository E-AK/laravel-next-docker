<?php

namespace App\Controller\Api\Task;

use App\ApiResource\TaskResource;
use App\Entity\Task;
use App\Service\TaskService;
use App\Service\UploadTaskService;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class StatusController extends AbstractController
{
    public function __construct(
        private readonly TaskService $taskService,
        private readonly UploadTaskService $uploadTaskService
    ) {

    }

    /**
     * @throws JsonException
     */
    #[Route('/api/task/status/{id}', methods: ['PATCH'])]
    public function execute(Task $task): JsonResponse
    {
        $this->denyAccessUnlessGranted('edit', $task);
        $task = $this->taskService->nextStatus($task);

        $this->uploadTaskService->uploadTask($task);

        return new TaskResource($task);
    }
}