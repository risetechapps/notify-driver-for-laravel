<?php

namespace RiseTechApps\Notify\Contracts;

interface NotifyContract
{
    public function toNotify($notifiable): MessageContract;
}
