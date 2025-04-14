<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Service\ImageService;
use Exception;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class ImageUploadController extends AbstractController
{
    public function __construct(
        private readonly ImageService $imageService,
    ) {
    }

    #[Route('/api/file/image', methods: ['POST'])]
    public function uploadImage(Request $request, CacheManager $imagineCacheManager): JsonResponse
    {
        $file = $this->getUploadedFile($request);
        if (!$file) {
            return $this->errorResponse('No image provided. Upload an image with "image" key.', Response::HTTP_BAD_REQUEST);
        }

        return $this->processImage($file, $request, $imagineCacheManager);
    }

    private function getUploadedFile(Request $request): ?UploadedFile
    {
        return $request->files->get('image');
    }

    private function processImage(UploadedFile $file, Request $request, CacheManager $imagineCacheManager): JsonResponse
    {
        try {
            $file = $this->imageService->convertUploadToWebp(
                $file,
                Uuid::fromString($request->headers->get('X-User-Id'))
            );

            $path = $file->getPath();
            $resolvedPath = $imagineCacheManager->getBrowserPath($path, 'thumbnail');

            if ( $request->get('avatar')) {
                $this->imageService->uploadAvatarId($file, $request->headers->get('Authorization'));
            }

            return $this->json([
                'path' => $path,
                'thumbnail' => $resolvedPath,
                'url' => $request->getSchemeAndHttpHost() . $path
            ]);
        } catch (Exception $e) {
            return $this->errorResponse('Image processing failed', Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    private function errorResponse(string $message, int $statusCode, string $errorDetail = ''): JsonResponse
    {
        $response = ['error' => $message];
        if ($errorDetail) {
            $response['message'] = $errorDetail;
        }

        return $this->json($response, $statusCode);
    }
}
