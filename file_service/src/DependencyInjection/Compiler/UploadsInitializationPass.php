<?php

declare(strict_types=1);

namespace App\DependencyInjection\Compiler;

use App\Service\UploadsInitializer;
use Exception;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class UploadsInitializationPass implements CompilerPassInterface
{
    /**
     * @throws Exception
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(UploadsInitializer::class)) {
            return;
        }

        $initializer = $container->get(UploadsInitializer::class);
        $initializer->initialize();
    }
}
