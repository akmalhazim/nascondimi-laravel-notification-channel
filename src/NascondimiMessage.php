<?php

namespace NotificationChannels\Nascondimi;

class NascondimiMessage
{
    /**
     * The WhatsApp content.
     *
     * @var string
     */
    public $content;

    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * Set content of the WhatsApp message.
     *
     * @param string $content
     *
     * @return self
     */
    public function content($content = '')
    {
        $this->content = $content;

        return $this;
    }
}
