<?php

namespace App\Scheduler;

use App\Repository\TaskNotificationRepository;
use App\Scheduler\Task\TaskNotification;
use App\Service\MailService;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule]
class NotificationSchedule implements ScheduleProviderInterface
{
    public function __construct(
        private MailService $mailService,
        private TaskNotificationRepository $taskNotificationRepository,
    ) {

    }

    public function getSchedule(): Schedule
    {
        return $this->schedule ??= (new Schedule())
            ->with(
                RecurringMessage::cron('3 8 * * 1', new TaskNotification($this->mailService, $this->taskNotificationRepository))
            );
    }
}