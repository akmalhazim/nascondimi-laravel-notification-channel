<?php

namespace NotificationChannels\Nascondimi\Traits;

trait HasMessage
{
    /**
     * Add new line to existing message.
     *
     * @param string $message
     *
     * @return self
     */
    public function line($message = '')
    {
        $message = trim($message);

        $this->payload['message'] = isset($this->payload['message']) && $this->payload['message'] != '' ? \sprintf('%s\n%s', $this->payload['message'], $message) : $message;

        return $this;
    }

    /**
     * Set/replace message payload.
     *
     * @param string $message
     *
     * @return $this
     */
    public function message(string $message)
    {
        $this->payload['message'] = trim($message);

        return $this;
    }

    /**
     * Render view file into message.
     *
     * @param string $view
     *
     * @return $this
     */
    public function view(string $view)
    {
        $this->payload['message'] = view($view)->render();

        return $this;
    }
}
