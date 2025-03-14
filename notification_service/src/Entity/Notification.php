<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\NotificationRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 */
#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Task $task = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $datetime = null;

    #[ORM\Column(length: 1023, nullable: false)]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $sent = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDatetime(): ?DateTimeInterface
    {
        return $this->datetime;
    }

    /**
     * @param  DateTimeInterface  $datetime
     *
     * @return $this
     */
    public function setDatetime(DateTimeInterface $datetime): static
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isSent(): ?bool
    {
        return $this->sent;
    }

    /**
     * @param  bool  $sent
     *
     * @return $this
     */
    public function setSent(bool $sent): static
    {
        $this->sent = $sent;

        return $this;
    }

    /**
     * @return Task
     */
    public function getTask(): Task
    {
        return $this->task;
    }

    /**
     * @param  Task  $task
     *
     * @return $this
     */
    public function setTask(Task $task): static
    {
        $this->task = $task;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }
}
