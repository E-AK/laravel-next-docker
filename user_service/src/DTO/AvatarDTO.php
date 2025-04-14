<?php

namespace App\DTO;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

class AvatarDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public Uuid $user_id,

        #[Assert\NotBlank]
        #[Assert\Uuid]
        public Uuid $file_id,
    ) {

    }
}