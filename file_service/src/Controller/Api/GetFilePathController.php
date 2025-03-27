<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GetFilePathController extends AbstractController
{
    public function __construct(
        private readonly FileService $fileService,
    ) {
    }

    #[Route('/api/file', methods: ['GET'])]
    public function execute(Request $request): JsonResponse
    {
        $path = $this->fileService->getPath($request);

        return $this->json([
            'path' => $path,
            'url' => $request->getSchemeAndHttpHost() . $path
        ]);
    }
}
