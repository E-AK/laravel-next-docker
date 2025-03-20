<?php

namespace App\Tests;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $kernel = static::bootKernel();
        $this->client = $kernel->getContainer()->get('test.client');

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