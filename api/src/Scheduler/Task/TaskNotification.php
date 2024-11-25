<?php

namespace App\Scheduler\Task;

use Symfony\Component\Scheduler\Attribute\AsCronTask;

#[AsCronTask('0 0 0 0 0')]
class TaskNotification
{
    public function __invoke()
    {

    }
}