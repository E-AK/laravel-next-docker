<?php

namespace App\Controller\Api;

use App\Entity\Task;
use App\Entity\User;
use App\Enums\TaskStatus;
use App\Model\TaskDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    private const ERROR = [
        'message' => 'Задача не найдена',
        'errors' => [
            'common' => 'Задача не найдена',
        ]
    ];

    public function __construct()
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

        $data = [
            'data' => [],
        ];

        foreach ($tasks as $task) {
            $data['data'][] = [
                'id' => $task->getId(),
                'text' => $task->getText(),
                'status' => $task->getStatus(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/api/task/create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] TaskDto $request,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $task = new Task();

        /**
         * @var User $user
         */
        $user = $this->getUser();

        $task->setText($request->text);
        $task->setStatus(TaskStatus::TODO);
        $task->setUser($user);

        $entityManager->persist($task);
        $entityManager->flush();

        return new JsonResponse([
            'data' => [
                'id' => $task->getId(),
                'text' => $task->getText(),
                'status' => $task->getStatus(),
            ],
        ]);
    }

    #[Route('/api/task/delete/{id}', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, Task $task): JsonResponse
    {
        $this->denyAccessUnlessGranted('delete', $task);

        $entityManager->remove($task);
        $entityManager->flush();

        return new JsonResponse([
            'data' => [
                'id' => $task->getId(),
                'text' => $task->getText(),
                'status' => $task->getStatus(),
            ],
        ]);
    }

    #[Route('/api/task/todo/{id}', methods: ['PATCH'])]
    public function todo(EntityManagerInterface $entityManager, Task $task): JsonResponse
    {
        return $this->changeStatus($task, $entityManager, TaskStatus::TODO);
    }

    #[Route('/api/task/does/{id}', methods: ['PATCH'])]
    public function does(EntityManagerInterface $entityManager, Task $task): JsonResponse
    {
        return $this->changeStatus($task, $entityManager, TaskStatus::DOES);
    }

    #[Route('/api/task/done/{id}', methods: ['PATCH'])]
    public function done(EntityManagerInterface $entityManager, Task $task): JsonResponse
    {
        return $this->changeStatus($task, $entityManager, TaskStatus::DONE);
    }

    #[Route('/api/task/edit/{id}', methods: ['PATCH'])]
    public function edit(
        #[MapRequestPayload] TaskDto $request,
        EntityManagerInterface $entityManager,
        Task $task
    ): JsonResponse {
        $this->denyAccessUnlessGranted('edit', $task);

        $task->setText($request->text);

        $entityManager->persist($task);
        $entityManager->flush();

        return new JsonResponse([
            'data' => [
                'id' => $task->getId(),
                'text' => $task->getText(),
                'status' => $task->getStatus(),
            ],
        ]);
    }

    private function changeStatus(Task $task, EntityManagerInterface $entityManager, TaskStatus $status): JsonResponse
    {
        $this->denyAccessUnlessGranted('edit', $task);

        $task->setStatus($status);

        $entityManager->persist($task);
        $entityManager->flush();

        return new JsonResponse([
            'data' => [
                'id' => $task->getId(),
                'text' => $task->getText(),
                'status' => $task->getStatus(),
            ],
        ]);
    }
}
