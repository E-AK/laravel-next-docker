<?php

namespace App\Message;

use App\Entity\Notification;

final readonly class SendEmailMessage
{
    /*
     * Add whatever properties and methods you need
     * to hold the data for this message class.
     */

     public function __construct(
         private Notification $notification,
     ) {

     }

     public function getNotification(): Notification
     {
         return $this->notification;
     }
}
