<?php

namespace App\Controller\Api\Task;

use App\ApiResource\TaskCollection;
use App\Entity\User;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class IndexController extends AbstractController
{
    public function __construct(
        private readonly TaskRepository $taskRepository
    ) {
    }

    #[Route('/api/task/index', methods: ['GET'])]
    public function execute(): TaskCollection
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $tasks = $this->taskRepository->findByUserId($user?->getId() ?? new Uuid('019570a4-e3bf-7504-99d4-c82f025cdf88'));

        return new TaskCollection($tasks);
    }
}