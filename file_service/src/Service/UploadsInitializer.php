<?php

declare(strict_types=1);

namespace App\Service;

use Exception;
use RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class UploadsInitializer
{
    private array $requiredDirectories = ['uploads_dir'];

    public function __construct(
        private readonly ParameterBagInterface $params,
        private readonly Filesystem $filesystem
    ) {
    }

    public function initialize(): void
    {
        foreach ($this->requiredDirectories as $dirKey) {
            $dirPath = $this->params->get($dirKey);
            $this->createDirectory($dirPath);
            $this->validateDirectory($dirPath);
        }
    }

    private function createDirectory(string $path): void
    {
        try {
            $this->filesystem->mkdir($path, 0775);
        } catch (Exception $e) {
            throw new RuntimeException("Failed to create directory: {$path}. " . $e->getMessage());
        }
    }

    private function validateDirectory(string $path): void
    {
        if (!$this->filesystem->exists($path)) {
            throw new RuntimeException("Directory does not exist: {$path}");
        }

        if (!is_writable($path)) {
            throw new RuntimeException("Directory is not writable: {$path}");
        }
    }
}
