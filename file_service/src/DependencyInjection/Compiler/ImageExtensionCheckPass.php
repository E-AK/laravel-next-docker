<?php

declare(strict_types=1);

namespace App\DependencyInjection\Compiler;

use RuntimeException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ImageExtensionCheckPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!function_exists('imagewebp')) {
            throw new RuntimeException(
                'WebP support is not available in GD. '.
                'Please install or enable GD extension with WebP support.'
            );
        }
    }
}
