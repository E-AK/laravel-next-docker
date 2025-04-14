<?php

namespace App\Controller\Api\User;

use App\DTO\AvatarDTO;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class AvatarController extends AbstractController
{
    #[Route('/api/user/avatar', name: 'update_avatar', methods: ['POST'])]
    public function execute(
        #[MapRequestPayload] AvatarDTO $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $user = $userRepository->find($request->user_id);

        if ($user === null) {
            throw $this->createNotFoundException();
        }

        try {
            $user->setAvatarId($request->file_id);
            $entityManager->persist($user);
            $entityManager->flush();

            return new JsonResponse(status: Response::HTTP_CREATED);
        } catch (Exception) {
            return new JsonResponse(
                status: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}