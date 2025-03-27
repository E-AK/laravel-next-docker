<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Service\FileService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FileUploadController extends AbstractController
{
    public function __construct(
        private readonly FileService $fileService
    ) {

    }

    #[Route('/api/file', methods: ['POST'])]
    public function execute(Request $request): JsonResponse
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('file');

        if (!$file) {
            return new JsonResponse(['error' => 'No file uploaded'], 400);
        }

        $filePath = $this->fileService->save($file, '/uploads/files');

        return new JsonResponse(
            [
                'status' => 'success',
                'path' => $filePath,
                'url' => $request->getSchemeAndHttpHost() . $filePath,
            ],
            Response::HTTP_CREATED
        );
    }
}
