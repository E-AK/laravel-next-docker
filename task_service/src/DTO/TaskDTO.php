<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class TaskDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public string $text,
    ) {

    }
}