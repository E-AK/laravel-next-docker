<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\FileRepository;
use Exception;
use RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

readonly class ImageService
{
    public function __construct(
        private ParameterBagInterface $params,
        private FileRepository $fileRepository,
    ) {
    }

    public function convertUploadToWebp(UploadedFile $file, int $quality = 80): string
    {
        $webpDir = $this->params->get('uploads_dir');
        $webpFilename = uniqid('', true) . '.webp';
        $webpPath = "$webpDir/$webpFilename";

        try {
            $image = $this->getImageResource($file);
            $this->saveAsWebp($image, $webpPath, $quality);
            imagedestroy($image);

            $path = $this->getRelativePath($webpPath);
            $this->fileRepository->save($path);

            return $path;
        } catch (Exception $e) {
            throw new RuntimeException('Image conversion failed: ' . $e->getMessage());
        }
    }

    private function getImageResource(UploadedFile $file)
    {
        return match ($file->getMimeType()) {
            'image/jpeg' => imagecreatefromjpeg($file->getRealPath()),
            'image/png' => $this->createFromPngWithTransparency($file->getRealPath()),
            default => throw new BadRequestHttpException('Invalid image type. Only JPEG and PNG are supported.'),
        };
    }

    private function saveAsWebp($image, string $path, int $quality): void
    {
        if (!imagewebp($image, $path, $quality) || !file_exists($path)) {
            throw new RuntimeException('Failed to create WebP image');
        }
    }

    private function createFromPngWithTransparency(string $path)
    {
        $image = imagecreatefrompng($path);
        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);
        return $image;
    }

    private function getRelativePath(string $fullPath): string
    {
        return str_replace(
            $this->params->get('kernel.project_dir') . '/public',
            '',
            $fullPath
        );
    }
}
