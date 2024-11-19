<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class TaskDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $text,
    ) {

    }
}