<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private ?Uuid $id;

    #[ORM\Column(length: 1023)]
    private ?string $text = null;

    #[ORM\Column(type: UuidType::NAME)]
    private ?Uuid $user_id = null;

    #[ORM\OneToMany(targetEntity: Notification::class, mappedBy: 'task')]
    private Collection $notifications;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(?Uuid $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

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

    public function getNotifications(): ?Collection
    {
        return $this->notifications;
    }
}
