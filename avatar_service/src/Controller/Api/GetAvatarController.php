<?php

namespace App\Controller\Api;

use App\Repository\AvatarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GetAvatarController extends AbstractController
{
    public function __construct(
        private readonly AvatarRepository $avatarRepository
    ) {

    }

    #[Route('/api/avatar', methods: ['GET'])]
    public function execute(Request $request)
    {
        $userId = $request->headers->get('X-User-Id');

        $avatar =  $this->avatarRepository->findOneByUserId($userId);

        if ($avatar === null) {
            throw $this->createNotFoundException();
        }

        return new JsonResponse(
            [
                'data' => [
                    'path' =>  $avatar->getPath(),
                ]
            ]
        );
    }
}