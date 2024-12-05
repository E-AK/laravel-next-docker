<?php

namespace App\Model;

use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

class NotificationDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public int $taskId,

        #[Assert\NotBlank]
        public DateTimeInterface $datetime,
    ) {

    }
}