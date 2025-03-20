<?php

namespace App\Tests\Unit;

use App\ApiResource\TokenResource;
use App\DTO\SignInDTO;
use App\DTO\SignUpDTO;
use App\Service\UserService;
use App\Tests\BaseTest;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SignInTest extends BaseTest
{
    private ValidatorInterface $validator;
    private UserService $userService;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->validator = $container->get(ValidatorInterface::class);
        $this->userService = $container->get(UserService::class);
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $container = static::getContainer();
        /**
         * @var UserService $userService
         */
        $userService = $container->get(UserService::class);

        $signUpDto = new SignUpDTO(
            'test1@test.test',
            '11111111',
            '11111111'
        );

        $userService->register($signUpDto);
    }

    public function testSuccessSignIn()
    {
        self::bootKernel();

        $signInDTO = new SignInDTO(
            'test1@test.test',
            '11111111',
        );
        
        $result = $this->userService->login($signInDTO);

        $this->assertInstanceOf(TokenResource::class, $result);
    }

    public function testInvalidEmail()
    {
        $signInDTO = new SignInDTO(
            'invalid-email',
            '11111111',
        );

        $violations = $this->validator->validate($signInDTO);
        $this->assertSame('email', $violations[0]->getPropertyPath());
    }

    public function testWrongPassword()
    {
        $signInDTO = new SignInDTO(
            'test1@test.test',
            'wrongpassword',
        );

        $this->expectException(AuthenticationException::class);
        $this->userService->login($signInDTO);
    }

    public function testEmptyEmail(): void
    {
        $signInDTO = new SignInDTO(
            '',
            '11111111',
        );

        $violations = $this->validator->validate($signInDTO);
        $this->assertSame('This value should not be blank.', $violations[0]->getMessage());
    }

    public function testEmptyPassword(): void
    {
        $signInDTO = new SignInDTO(
            'test1@test.test',
            '',
        );

        $violations = $this->validator->validate($signInDTO);
        $this->assertSame('This value should not be blank.', $violations[0]->getMessage());
    }

    public function testShortPassword(): void
    {
        $signInDTO = new SignInDTO(
            'test1@test.test',
            '11111',
        );

        $violations = $this->validator->validate($signInDTO);
        $this->assertSame('This value is too short. It should have 8 characters or more.', $violations[0]->getMessage());
    }

    public function testNonExistentEmail(): void
    {
        $signInDTO = new SignInDTO(
            'nonexistent@test.test',
            '11111111',
        );

        $this->expectException(AuthenticationException::class);
        $this->userService->login($signInDTO);
    }
}