<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\FileRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class FileService
{
    public function __construct(
        private ParameterBagInterface $params,
        private FileRepository $fileRepository,
    ) {

    }

    public function save(UploadedFile  $file, string $uploadsRelativeDir): string
    {
        $uploadsDir = $this->params->get('kernel.project_dir') . '/public'.$uploadsRelativeDir;
        $filename = uniqid('', true) . '.' . $file->guessExtension();
        $file->move($uploadsDir, $filename);

        return $uploadsRelativeDir . '/' . $filename;
    }

    public function getPath(Request $request): string
    {
        $fileId = $request->get('file_id');

        $file = $this->fileRepository->find($fileId);

        if (!$file) {
            throw new NotFoundHttpException();
        }

        return $file->getPath();
    }
}
