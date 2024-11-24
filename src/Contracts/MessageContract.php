<?php

namespace RiseTechApps\Notify\Contracts;

use RiseTechApps\Notify\Message\MessageEmailNotify;

interface MessageContract
{
    public function notifyEMail(): MessageEmailNotify;
    public function notifySMS(): static;
    public function payload(array $payload): static;
}
