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
            'data' => $tasks,
        ];

        $serializer = JMS\Serializer\SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($data, 'json');

        return new JsonResponse($serializer);
    }

    /**
     * @throws \JsonException
     */
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

        if (is_null($user)) {
            return new JsonResponse([
                'message' => 'Пользователь не найден',
                'error' => [
                    'common' => 'Пользователь не найден',
                ]
            ]);
        }

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
    public function delete(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (is_null($task)) {
            return new JsonResponse(self::ERROR, 404);
        }

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
    public function todo(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        return $this->changeStatus($id, $entityManager, TaskStatus::TODO);
    }

    #[Route('/api/task/does/{id}', methods: ['PATCH'])]
    public function does(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        return $this->changeStatus($id, $entityManager, TaskStatus::DOES);
    }

    #[Route('/api/task/done/{id}', methods: ['PATCH'])]
    public function done(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        return $this->changeStatus($id, $entityManager, TaskStatus::DONE);
    }

    #[Route('/api/task/edit/{id}', methods: ['PATCH'])]
    public function edit(
        #[MapRequestPayload] TaskDto $request,
        EntityManagerInterface $entityManager,
        int $id
    ): JsonResponse {
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (is_null($task)) {
            return new JsonResponse(self::ERROR, 404);
        }

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

    private function changeStatus(int $id, EntityManagerInterface $entityManager, TaskStatus $status): JsonResponse
    {
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (is_null($task)) {
            return new JsonResponse(self::ERROR, 404);
        }

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
