<?php

namespace App\Controller\Api\Task;

use App\ApiResource\TaskResource;
use App\DTO\TaskDTO;
use App\Entity\User;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    ): TaskResource {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        return $this->taskService->create($request, $user->getId());
    }
}