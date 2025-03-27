<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: FileRepository::class)]
class File
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $path = null;

    #[ORM\Column(type: UuidType::NAME, nullable: false)]
    private ?Uuid $user_id = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getUserId(): ?Uuid
    {
        return $this->user_id;
    }

    public function setUserId(?Uuid $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }
}
