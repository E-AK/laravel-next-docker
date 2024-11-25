<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class SigninDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,

        #[Assert\NotBlank]
        #[Assert\Length(min: 8)]
        public string $password,
    ) {

    }
}