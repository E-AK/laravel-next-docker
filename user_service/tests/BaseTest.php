<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BaseTest extends KernelTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::bootKernel();
        $application = new Application(self::$kernel);
        TestHelper::setup($application);
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        self::bootKernel();
        $application = new Application(self::$kernel);
        TestHelper::down($application);
    }
}