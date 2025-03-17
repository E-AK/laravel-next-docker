<?php

namespace App\Event;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

class RegisterEvent extends Event
{
    public const NAME = 'register';

    public function __construct(
        private readonly UserInterface $user
    )
    {

    }

    public function getUser()
    {
        return $this->user;
    }
}