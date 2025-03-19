<?php

namespace App\Tests\Unit;

use App\Consumer\MailSenderConsumer;
use App\Message\SendEmailMessage;
use App\Service\MailService;
use PHPUnit\Framework\TestCase;

class MailSenderConsumerTest extends TestCase
{
    public function testInvoke()
    {
        $mailServiceMock = $this->createMock(MailService::class);

        $message = new SendEmailMessage(
            'testemail@test.com',
            'Test',
        );

        $mailServiceMock->expects($this->once())
            ->method('sendEmail')
            ->with($message);

        $consumer = new MailSenderConsumer($mailServiceMock);

        $consumer($message);
    }
}