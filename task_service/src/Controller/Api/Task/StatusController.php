<?php

namespace App\Controller\Api\Task;

use App\ApiResource\TaskResource;
use App\Entity\Task;
use App\Service\TaskService;
use App\Service\UploadTaskService;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

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
    #[Route('/api/tasks/status/{id}', methods: ['PATCH'])]
    public function execute(Task $task): JsonResponse
    {
        $task = $this->taskService->nextStatus($task);

        $this->uploadTaskService->uploadTask($task);

        return new TaskResource($task);
    }
}