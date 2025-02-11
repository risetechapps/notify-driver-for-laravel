<?php

namespace RiseTechApps\Notify\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Notifications\Notification;

class NotifySentEvent
{
    use Dispatchable;

    public $notifiable;
    public $notification;
    public $response;

    public function __construct($notifiable, Notification $notification, $response)
    {
        $this->notifiable = $notifiable;
        $this->notification = $notification;
        $this->response = $response;
    }
}
