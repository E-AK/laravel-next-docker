<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class ConstrainsUniqueEmail extends Constraint
{
    public function __construct(
        public string $mode = 'strict',
        public string $message = 'Email must be unique',
        ?array $groups = null,
        $payload = null
    )
    {
        parent::__construct([], $groups, $payload);
    }

    public function validatedBy(): string
    {
        return static::class.'Validator';
    }
}