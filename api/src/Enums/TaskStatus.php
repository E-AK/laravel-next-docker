<?php

namespace App\Enums;

enum TaskStatus: string
{
    case TODO = 'todo';
    case DOES = 'does';
    case DONE = 'done';
}
