<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as UserAssert;

class SignUpDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        #[UserAssert\ConstrainsUniqueEmail]
        public string $email,

        #[Assert\NotBlank]
        #[Assert\Length(min: 8)]
        public string $password,

        #[Assert\NotBlank]
        #[Assert\Length(min: 8)]
        #[Assert\EqualTo(propertyPath: 'password')]
        public string $repeat_password,
    ) {

    }
}