<?php

namespace App\Controller\Api\Tasks;

use App\ApiResource\TaskResource;
use App\Model\TaskDto;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateController extends AbstractController
{
    public function __construct(
        private readonly TaskService $taskService,
    ) {

    }

    #[Route('/api/task/create', methods: ['POST'])]
    public function execute(
        #[MapRequestPayload] TaskDto $request,
    ): JsonResponse {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $task = $this->taskService->createTask($request, $user);

        return new TaskResource($task);
    }
}