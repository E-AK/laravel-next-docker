<?php

namespace App\Tests;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class TestHelper
{
    public static function setup(Application $application)
    {
        $application->setAutoExit(false);

        $application->run(new ArrayInput([
            'command' => 'doctrine:database:create',
            '--if-not-exists' => true,
            '--env' => 'test',
        ]));

        $application->run(new ArrayInput([
            'command' => 'doctrine:migrations:migrate',
            '--no-interaction' => true,
            '--env' => 'test',
        ]));

        $application->run(new ArrayInput([
            'command' => 'lexik:jwt:generate-keypair',
            '--skip-if-exists' => true,
        ]));
    }

    public static function down(Application $application)
    {
        $application->setAutoExit(false);

        $application->run(new ArrayInput([
            'command' => 'doctrine:database:drop',
            '--force' => true,
            '--env' => 'test',
        ]));
    }
}