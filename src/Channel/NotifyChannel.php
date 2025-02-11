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
        $this->apiUrl = config('services.notify.url');
        $this->apiKey = config('services.notify.key');
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
                'Authorization' => $this->apiKey,
            ])->post("{$this->apiUrl}/api/send/" . $type, $payload->toArray());

            if ($response->failed()) {
                throw new Exception('Error sending notification: ' . $response->body());
            }

            Event::dispatch(new NotifySentEvent($notifiable, $notification, $response->json()));


            return $response->json();
        } catch (\Exception $exception) {
            Event::dispatch(new NotifyFailedEvent($notifiable, $notification, $exception));

            throw $exception;
        }
    }
}
