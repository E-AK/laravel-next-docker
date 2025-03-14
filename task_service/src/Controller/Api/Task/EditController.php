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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

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
    #[Route('/api/tasks/edit/{id}', methods: ['PATCH'])]
    public function execute(
        #[MapRequestPayload] TaskDTO $taskDTO,
        Task $task
    ): JsonResponse {
        $task = $this->taskService->updateTask($task, null, $taskDTO->text);

        $this->uploadTaskService->uploadTask($task);

        return new TaskResource($task);
    }
}