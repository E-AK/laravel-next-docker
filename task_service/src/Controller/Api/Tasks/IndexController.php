<?php

namespace App\Controller\Api\Tasks;

use App\ApiResource\TaskCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/api/task/index', methods: ['GET'])]
    public function execute(): JsonResponse
    {
        /**
         * TODO: get user
         */
        $user = $this->getUser();

        $tasks = $user->getTasks();

        return new TaskCollection($tasks);
    }
}