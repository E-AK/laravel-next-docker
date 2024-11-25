<?php

namespace App\Service;

use App\ApiResource\TaskResource;
use App\Entity\Task;
use App\Entity\User;
use App\Enums\TaskStatus;
use App\Model\TaskDto;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {

    }

    public function createTask(TaskDto $request, User $user): Task
    {
        $task = new Task();

        $task->setText($request->text);
        $task->setStatus(TaskStatus::TODO);
        $task->setUser($user);

        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public function deleteTask(Task $task): void
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }

    public function updateTask(Task $task, ?TaskStatus $status = null, ?string $text = null): TaskResource
    {
        if (!is_null($status)) {
            $task->setStatus($status);
        }

        if (!is_null($text)) {
            $task->setText($text);
        }

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return new TaskResource($task);
    }
}