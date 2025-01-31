<?php

namespace App\Controller\Api\Task;

use App\ApiResource\TaskResource;
use App\DTO\TaskDTO;
use App\Entity\User;
use App\Service\TaskService;
use App\Service\UploadTaskService;
use JsonException;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

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
        $user = $this->getUser();

        $task = $this->taskService->create($request, $user->getId());

        $this->uploadTaskService->uploadTask($task);

        return new TaskResource($task);
    }
}