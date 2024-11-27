<?php

namespace App\Controller\Api;

use App\ApiResource\TaskCollection;
use App\ApiResource\TaskResource;
use App\Entity\Task;
use App\Entity\TaskNotification;
use App\Entity\User;
use App\Enums\TaskStatus;
use App\Model\NotificationDTO;
use App\Model\TaskDto;
use App\Service\NotificationService;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    public function __construct(
        private readonly TaskService $taskService,
        private readonly NotificationService $notificationService
    )
    {

    }

    #[Route('/api/task/index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $tasks = $user->getTasks();

        return new TaskCollection($tasks);
    }

    #[Route('/api/task/create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] TaskDto $request,
    ): JsonResponse {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $task = $this->taskService->createTask($request, $user);

        return new TaskResource($task);
    }

    #[Route('/api/task/delete/{id}', methods: ['DELETE'])]
    public function delete(Task $task): JsonResponse
    {
        $this->denyAccessUnlessGranted('delete', $task);

        $this->taskService->deleteTask($task);

        return new TaskResource($task);
    }

    #[Route('/api/task/todo/{id}', methods: ['PATCH'])]
    public function todo(Task $task): JsonResponse
    {
        return $this->changeStatus($task, TaskStatus::TODO);
    }

    #[Route('/api/task/does/{id}', methods: ['PATCH'])]
    public function does(Task $task): JsonResponse
    {
        return $this->changeStatus($task,TaskStatus::DOES);
    }

    #[Route('/api/task/done/{id}', methods: ['PATCH'])]
    public function done(Task $task): JsonResponse
    {
        return $this->changeStatus($task, TaskStatus::DONE);
    }

    #[Route('/api/task/edit/{id}', methods: ['PATCH'])]
    public function edit(
        #[MapRequestPayload] TaskDto $request,
        Task $task
    ): JsonResponse {
        $this->denyAccessUnlessGranted('edit', $task);
        return $this->taskService->updateTask($task, null, $request->text);
    }

    private function changeStatus(Task $task, TaskStatus $status): JsonResponse
    {
        $this->denyAccessUnlessGranted('edit', $task);
        return $this->taskService->updateTask($task, $status);
    }
}
