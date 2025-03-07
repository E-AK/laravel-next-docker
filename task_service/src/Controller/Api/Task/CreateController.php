<?php

namespace App\Controller\Api\Task;

use App\ApiResource\TaskResource;
use App\DTO\TaskDTO;
use App\Entity\User;
use App\Service\TaskService;
use App\Service\UploadTaskService;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

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
    #[Route('/api/task/create', methods: ['POST'])]
    public function execute(
        #[MapRequestPayload] TaskDto $request,
    ): TaskResource {
        /**
         * @var User $user
         */
//        $user = $this->getUser();

        $task = $this->taskService->create($request, new Uuid('019570a4-e3bf-7504-99d4-c82f025cdf88'));

        $this->uploadTaskService->uploadTask($task);

        return new TaskResource($task);
    }
}