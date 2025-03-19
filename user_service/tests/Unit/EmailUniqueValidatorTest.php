<?php

namespace App\Tests\Unit;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Validator\ConstrainsUniqueEmail;
use App\Validator\ConstrainsUniqueEmailValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class EmailUniqueValidatorTest extends ConstraintValidatorTestCase
{
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);

        parent::setUp();
    }

    protected function createValidator(): ConstrainsUniqueEmailValidator
    {
        return new ConstrainsUniqueEmailValidator($this->userRepository);
    }

    public function testValidEmail()
    {
        $this->userRepository->method('findByEmail')
            ->willReturn(null);

        $this->validator->validate('test@example.com', new ConstrainsUniqueEmail());

        $this->assertNoViolation();
    }

    public function testInvalidEmail()
    {
        $this->userRepository->method('findByEmail')
            ->willReturn(new User());

        $this->validator->validate('existing@example.com', new ConstrainsUniqueEmail());

        $this->buildViolation('Email must be unique')
            ->assertRaised();
    }

    public function testEmptyEmail()
    {
        $this->validator->validate('', new ConstrainsUniqueEmail());

        $this->assertNoViolation();
    }
}