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
        #[Assert\Regex(
            pattern: '/^[A-Za-z]{3}, \d{2} [A-Za-z]{3} \d{4} \d{2}:\d{2}:\d{2} GMT$/'
        )]
        public string $datetime,
    ) {

    }
}