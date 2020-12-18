<?php

namespace NotificationChannels\Nascondimi;

use Illuminate\Notifications\Notification;
use NotificationChannels\Nascondimi\Exceptions\CouldNotSendNotification;
use NotificationChannels\Nascondimi\Exceptions\InvalidConfigException;

class NascondimiChannel
{
    /** @var Nascondimi Nascondimi WhatsApp client. */
    protected $nascondimi;

    /**
     * Channel constructor.
     *
     * @param Whatsapp $whatsapp
     */
    public function __construct(Nascondimi $nascondimi)
    {
        $this->nascondimi = $nascondimi;
    }

    /**
     * Send the given notification.
     *
     * @param mixed        $notifiable
     * @param Notification $notification
     *
     * @throws CouldNotSendNotification|InvalidConfigException
     *
     * @return null|array
     */
    public function send($notifiable, Notification $notification): ?array
    {
        $message = $notification->toNascondimi($notifiable);

        if (is_string($message)) {
            $message = new NascondimiMessage($message);
        }
        if (!$to = $notifiable->routeNotificationFor('nascondimi', $notification)) {
            return null;
        }

        $params = [
            'phone_number' => $to,
            'message'      => trim($message->content),
        ];

        return $this->nascondimi->send($params);
    }
}
