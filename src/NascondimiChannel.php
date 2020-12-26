<?php

namespace NotificationChannels\Nascondimi;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use NotificationChannels\Nascondimi\Exceptions\CouldNotSendNotification;
use NotificationChannels\Nascondimi\Exceptions\InvalidConfigException;
use NotificationChannels\Nascondimi\Contracts\Uploadable;

class NascondimiChannel
{
    /** @var Nascondimi */
    protected $nascondimi;

    /** @var Dispatcher */
    protected $events;

    /**
     * Channel constructor.
     *
     * @param Whatsapp $whatsapp
     */
    public function __construct(Nascondimi $nascondimi, Dispatcher $events)
    {
        $this->nascondimi = $nascondimi;
        $this->events = $events;
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
        try {
            $message = $notification->toNascondimi($notifiable);

            if (is_string($message)) {
                $message = (new NascondimiMessage())->line($message);
            }
            if ($message->toNotGiven()) {
                if(!$to = $notifiable->routeNotificationFor('nascondimi', $notification)) {
                    return null;
                }
                $message->to($to);
            }

            if($message instanceof Uploadable) {
                Log::info(\sprintf('Uploading file to Nascondimi server ... - %s', $message->getFileName()));
                $filename = $this->nascondimi->uploadFile([
                    'file' => $message->getFileContents(),
                    'filename' => $message->getFileName()
                ]);
            }
            $params = $message->toArray();

            Log::info('Sending WhatsApp message to '.$message->getPhone());
            Log::debug('WhatsApp params -'.json_encode($params));

            return $this->nascondimi->send($message->getEndpoint(), $params);
        } catch(Exception $exception) {
            Log::error('Error communicating with Nascondimi - '.$exception->getMessage());

            $event = new NotificationFailed(
                $notifiable,
                $notification,
                'nascondimi',
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception,
                ]
            );

            $this->events->dispatch($event);

            throw $exception;
        }
    }
}
