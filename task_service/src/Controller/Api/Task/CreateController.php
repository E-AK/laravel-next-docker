<?php

namespace App\Controller\Api\Task;

use App\ApiResource\TaskResource;
use App\DTO\TaskDTO;
use App\Entity\User;
use App\Service\TaskService;
use App\Service\UploadTaskService;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class CreateController extends AbstractController
{
    public function __construct(
        private readonly TaskService $taskService,
        private readonly UploadTaskService $uploadTaskService
    ) {

    }

    /**
     * @throws JsonException
     */
    #[Route('/api/tasks/create', methods: ['POST'])]
    public function execute(
        #[MapRequestPayload] TaskDto $taskDTO,
        Request $request
    ): TaskResource {
        $userId = $request->headers->get('X-User-Id');

        if ($userId === null) {
            throw new AuthenticationException('Missing user id');
        }

        $task = $this->taskService->create($taskDTO, $userId);

        $this->uploadTaskService->uploadTask($task);

        return new TaskResource($task);
    }
}