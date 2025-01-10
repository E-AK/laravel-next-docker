<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

class User implements UserInterface
{
    private ?Uuid $id;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(?Uuid $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getRoles(): array
    {
        return [];
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->getId();
    }
}