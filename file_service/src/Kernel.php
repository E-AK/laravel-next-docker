<?php

namespace App;

use App\DependencyInjection\Compiler\ImageExtensionCheckPass;
use App\DependencyInjection\Compiler\UploadsInitializationPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ImageExtensionCheckPass());
        $container->addCompilerPass(new UploadsInitializationPass());
    }
}
