<?php

namespace RiseTechApps\Notify\Message;

class MessageSMSNotify
{
    protected $notifiable;
    protected MessageNotify $messageNotify;
    protected ?string $message = null;
    protected ?string $cellphone = null;

    public function __construct($notifiable, MessageNotify $messageNotify)
    {
        $this->notifiable = $notifiable;
        $this->messageNotify = $messageNotify;

        $this->getCellphone();
    }

    private function getCellphone(): void
    {
        $this->cellphone = $this->notifiable->routeNotificationForCellphone();
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;
        return $this;
    }

    public function setCellphone(string $cellphone): static
    {
        $this->cellphone = $cellphone;
        return $this;
    }

    public function send(): MessageNotify
    {
        return $this->messageNotify;
    }

    public function toArray(): array
    {
        return [
            'cellphone' => $this->cellphone,
            'message' => $this->message,
            'app_name' => config('services.notify.from_name')
        ];
    }

}
