<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class SignupDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 11)]
        #[Assert\Length(max: 11)]
        public string $login,

        #[Assert\NotBlank]
        #[Assert\Length(min: 8)]
        public string $password,

        #[Assert\NotBlank]
        #[Assert\Length(min: 8)]
        public string $repeat_password,
    ) {

    }
}