<?php

namespace RiseTechApps\Notify\Message;

use RiseTechApps\Notify\Contracts\MessageContract;

class MessageNotify implements MessageContract
{
    protected string $type;
    protected $payload;
    protected $notifiable;

    public function __construct($notifiable)
    {
        $this->notifiable = $notifiable;
    }

    public function payload(array $payload): static
    {
        $this->payload = $payload;
        return $this;
    }

    public function notifyEmail(): MessageEmailNotify
    {
        $this->type = 'email';
        $this->payload = (new MessageEmailNotify($this->notifiable, $this));
        return $this->payload;
    }

    public function notifySMS(): MessageSMSNotify
    {
        $this->type = 'sms';
        $this->payload = (new MessageSMSNotify($this->notifiable, $this));
        return $this->payload;
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
