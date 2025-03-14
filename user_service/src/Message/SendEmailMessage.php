<?php

namespace App\Message;

final readonly class SendEmailMessage
{
     public function __construct(
         private string $email,
         private string $text
     ) {

     }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
