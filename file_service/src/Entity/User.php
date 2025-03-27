<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private string $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    public function getUserIdentifier(): string
    {
        return $this->userId;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {

    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return new User($identifier);
    }

    public function supportsClass(string $class): bool
    {
        return __CLASS__ === $class || is_subclass_of($class, __CLASS__);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Unsupported user class "%s"', get_class($user)));
        }

        return $user;
    }
}
