<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\FileRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

readonly class FileService
{
    public function __construct(
        private ParameterBagInterface $params,
        private FileRepository $fileRepository,
    ) {

    }

    public function save(
        UploadedFile $file,
        Uuid $userId,
        string $uploadsRelativeDir
    ): string {
        $uploadsDir = $this->params->get('kernel.project_dir')
            . '/public'.$uploadsRelativeDir;
        $filename = uniqid('', true)
            . '.' . $file->guessExtension();
        $file->move($uploadsDir, $filename);

        $path = $uploadsRelativeDir . '/' . $filename;

        $this->fileRepository->save($path, $userId);

        return $path;
    }

    public function getPath(string $fileId): string
    {
        $file = $this->fileRepository->find($fileId);

        if (!$file) {
            throw new NotFoundHttpException();
        }

        return $file->getPath();
    }
}
