<?php

namespace RiseTechApps\Notify\Contracts;

use RiseTechApps\Notify\Message\MessageEmailNotify;
use RiseTechApps\Notify\Message\MessageSMSNotify;

interface MessageContract
{
    public function notifyEMail(): MessageEmailNotify;

    public function notifySMS(): MessageSMSNotify;

    public function payload(array $payload): static;
}
