<?php

namespace App\Service;

use App\ApiResource\TaskResource;
use App\DTO\TaskDTO;
use App\Entity\Task;
use App\Enums\TaskStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

readonly class TaskService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {

    }

    public function create(TaskDTO $request, string $userId): Task
    {
        $task = new Task();

        $task->setText($request->text);
        $task->setStatus(TaskStatus::TODO);
        $task->setUserId(new Uuid($userId));

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    public function deleteTask(Task $task): void
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }

    public function updateTask(Task $task, ?TaskStatus $status = null, ?string $text = null): Task
    {
        if (!is_null($status)) {
            $task->setStatus($status);
        }

        if (!is_null($text)) {
            $task->setText($text);
        }

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    public function nextStatus(Task $task): Task
    {
        $status = match ($task->getStatus()) {
            TaskStatus::TODO => TaskStatus::DOES,
            TaskStatus::DOES => TaskStatus::DONE,
            TaskStatus::DONE => TaskStatus::TODO,
        };

        $task->setStatus($status);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }
}