<?php

namespace RiseTechApps\Notify\Events;

use Exception;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Notifications\Notification;

class NotifyFailedEvent
{
    use Dispatchable;

    public $notifiable;
    public $notification;
    public $exception;

    public function __construct($notifiable, Notification $notification, Exception $exception)
    {
        $this->notifiable = $notifiable;
        $this->notification = $notification;
        $this->exception = $exception;
    }
}
