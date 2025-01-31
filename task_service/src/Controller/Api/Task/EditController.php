<?php

namespace App\Controller\Api\Task;

use App\ApiResource\TaskResource;
use App\DTO\TaskDTO;
use App\Entity\Task;
use App\Service\TaskService;
use App\Service\UploadTaskService;
use JsonException;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class EditController extends AbstractController
{
    public function __construct(
        private readonly TaskService $taskService,
        private readonly UploadTaskService $uploadTaskService
    ) {

    }

    /**
     * @throws JsonException
     */
    #[Route('/api/task/edit/{id}', methods: ['PATCH'])]
    public function execute(
        #[MapRequestPayload] TaskDTO $request,
        Task $task
    ): JsonResponse {
        $this->denyAccessUnlessGranted('edit', $task);

        $task = $this->taskService->updateTask($task, null, $request->text);

        $this->uploadTaskService->uploadTask($task);

        return new TaskResource($task);
    }
}