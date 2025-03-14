<?php

namespace App\Controller\Api\Task;

use App\ApiResource\TaskCollection;
use App\Entity\User;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class IndexController extends AbstractController
{
    public function __construct(
        private readonly TaskRepository $taskRepository
    ) {
    }

    #[Route('/api/tasks/index', methods: ['GET'])]
    public function execute(Request $request): TaskCollection
    {
        $userId = $request->headers->get('X-User-Id');

        $tasks = $this->taskRepository->findByUserId(new Uuid($userId));

        return new TaskCollection($tasks);
    }
}