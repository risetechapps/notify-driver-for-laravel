<?php

namespace RiseTechApps\Notify\Channel;

use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use RiseTechApps\Notify\Contracts\MessageContract;
use RiseTechApps\Notify\Events\NotifyFailedEvent;
use RiseTechApps\Notify\Events\NotifySendingEvent;
use RiseTechApps\Notify\Events\NotifySentEvent;

class NotifyChannel
{
    protected string $apiUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->apiUrl = "https://notify.risetech.dev.br";
        $this->apiKey = config('notify.key');
    }

    /**
     * @throws Exception
     */
    public function send($notifiable, Notification $notification)
    {

        try {
            $data = $notification->toNotify($notifiable);

            if (!$data instanceof MessageContract) {
                throw new Exception('Invalid notification type.');
            }

            $type = $data->getType();
            $payload = $data->getPayload();

            Event::dispatch(new NotifySendingEvent($notifiable, $notification));

            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
            ])->post("{$this->apiUrl}/api/v1/send/" . $type, $payload->toArray());

            if ($response->failed()) {
                throw new Exception('Error sending notification: ' . $response->body());
            }

            $responseJson = $response->json();

            Event::dispatch(new NotifySentEvent($notifiable, $notification, $responseJson));

            logglyInfo()->performedOn(self::class)
                ->withProperties(['notifiable' => $notifiable, 'notification' => $notification, 'response' => $responseJson])
                ->withTags(['action' => 'send'])->log("Notification sent");

            return $responseJson;
        } catch (\Exception $exception) {
            Event::dispatch(new NotifyFailedEvent($notifiable, $notification, $exception));

            logglyError()->performedOn(self::class)
                ->withProperties(['notifiable' => $notifiable, 'notification' => $notification])
                ->exception($exception)->withTags(['action' => 'send'])->log("Error by sending notification");

            throw $exception;
        }
    }
}
