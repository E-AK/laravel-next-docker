<?php

namespace App\Tests\Unit;

use App\ApiResource\TokenResource;
use App\DTO\SignUpDTO;
use App\Entity\User;
use App\Message\SendEmailMessage;
use App\Repository\UserRepository;
use App\Service\UserService;
use App\Tests\BaseTest;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;
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

        $signUpDto = new SignUpDTO(
            'test@test.test',
            '11111111',
            '11111111'
        );

        $result = $this->userService->register($signUpDto);

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

    public function testEmptyEmail(): void
    {
        $signUpDto = new SignUpDTO(
            '',
            '11111111',
            '11111111'
        );

        $violations = $this->validator->validate($signUpDto);
        $this->assertSame('This value should not be blank.', $violations[0]->getMessage());
    }

    public function testEmptyPassword(): void
    {
        $signUpDto = new SignUpDTO(
            'test@test.test',
            '',
            '11111111'
        );

        $violations = $this->validator->validate($signUpDto);
        $this->assertSame('This value should not be blank.', $violations[0]->getMessage());
    }

    public function testEmptyRepeatPassword(): void
    {
        $signUpDto = new SignUpDTO(
            'test@test.test',
            '11111111',
            ''
        );

        $violations = $this->validator->validate($signUpDto);
        $this->assertSame('This value should not be blank.', $violations[0]->getMessage());
    }
}