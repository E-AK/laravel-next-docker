<?php

namespace App\Model;

class SigninDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 11)]
        #[Assert\Length(max: 11)]
        public string $login,

        #[Assert\NotBlank]
        #[Assert\Length(min: 8)]
        public string $password,
    ) {

    }
}