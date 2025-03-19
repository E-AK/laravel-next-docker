<?php

namespace App\Tests\Unit;

use App\ApiResource\TokenResource;
use App\DTO\SignUpDTO;
use App\Entity\User;
use App\Message\SendEmailMessage;
use App\Repository\UserRepository;
use App\Service\UserService;
use App\Tests\BaseTest;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Symfony\Component\Validator\ContainerConstraintValidatorFactory;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SignUpTest extends BaseTest
{
    private ValidatorInterface $validator;
    private UserService $userService;
    private MessageBusInterface $messageBus;
    private TransportInterface $transport;

    protected function setUp(): void
    {
        $container = static::getContainer();
        $this->validator = $container->get(ValidatorInterface::class);
        $this->userService = $container->get(UserService::class);
        $this->messageBus = $container->get(MessageBusInterface::class);
        $this->transport = $container->get('messenger.transport.async');
    }

    public function testSuccessSignUp()
    {
        self::bootKernel();
        $container = static::getContainer();

        /**
         * @var UserService $userService
         */
        $userService = $container->get(UserService::class);

        $signUpDto = new SignUpDTO(
            'test@test.test',
            '11111111',
            '11111111'
        );

        $violations = $this->validator->validate($signUpDto);

        $this->assertCount(0, $violations);

        $result = $userService->register($signUpDto);

        $this->assertInstanceOf(TokenResource::class, $result);
    }

    public function testNotConfirmedPassword()
    {
        self::bootKernel();

        $signUpDto = new SignUpDTO(
            'test2@test.test',
            '11111111',
            '11111112'
        );

        $violations = $this->validator->validate($signUpDto);

        $this->assertCount(1, $violations);
        $this->assertSame('repeat_password', $violations[0]->getPropertyPath());
    }

    public function testInvalidEmail()
    {
        $signUpDto = new SignUpDTO(
            'invalid-email',
            '11111111',
            '11111111'
        );

        $violations = $this->validator->validate($signUpDto);

        $this->assertGreaterThan(0, count($violations));
        $this->assertSame('email', $violations[0]->getPropertyPath());
    }

    public function testInvalidPassword()
    {
        $signUpDto = new SignUpDTO(
            'test@test.com',
            '123',
            '123'
        );

        $violations = $this->validator->validate($signUpDto);

        $this->assertGreaterThan(0, count($violations));
        $this->assertSame('password', $violations[0]->getPropertyPath());
    }

    public function testDuplicateEmail()
    {
        $signUpDto = new SignUpDTO(
            'test@test.test',
            '11111111',
            '11111111'
        );

        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock->method('findOneBy')->willReturn(new User());

        $violations = $this->validator->validate($signUpDto);

        $this->assertGreaterThan(0, count($violations));
        $this->assertSame('email', $violations[0]->getPropertyPath());
        $this->assertStringContainsString('Email must be unique', $violations[0]->getMessage());
    }

    public function testEmailSentOnSuccessfulRegistration()
    {
        $signUpDto = new SignUpDTO(
            'testemail@test.com',
            '11111111',
            '11111111'
        );

        $violations = $this->validator->validate($signUpDto);
        $this->assertCount(0, $violations);

        $this->userService->register($signUpDto);

        $envelopes = iterator_to_array($this->transport->get());

        $this->assertGreaterThan(0, count($envelopes));

        $foundEmailMessage = false;
        foreach ($envelopes as $envelope) {
            $message = $envelope->getMessage();
            if ($message instanceof SendEmailMessage) {
                $foundEmailMessage = true;
                break;
            }
        }

        $this->assertTrue($foundEmailMessage);
    }
}