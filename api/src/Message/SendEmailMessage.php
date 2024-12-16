<?php

namespace App\Message;

use App\Entity\TaskNotification;

final readonly class SendEmailMessage
{
    /*
     * Add whatever properties and methods you need
     * to hold the data for this message class.
     */

     public function __construct(
         private TaskNotification $notification,
     ) {

     }

     public function getNotification(): TaskNotification
     {
         return $this->notification;
     }
}
