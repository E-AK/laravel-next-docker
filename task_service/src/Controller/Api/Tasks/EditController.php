<?php

namespace App\Controller\Api\Tasks;

use App\Entity\Task;
use App\Model\TaskDto;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class EditController extends AbstractController
{
    public function __construct(
        private readonly TaskService $taskService,
    ) {

    }

    #[Route('/api/task/edit/{id}', methods: ['PATCH'])]
    public function execute(
        #[MapRequestPayload] TaskDto $request,
        Task $task
    ): JsonResponse {
        $this->denyAccessUnlessGranted('edit', $task);
        return $this->taskService->updateTask($task, null, $request->text);
    }
}