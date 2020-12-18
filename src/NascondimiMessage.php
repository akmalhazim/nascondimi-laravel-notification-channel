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
     * Set the content of the WhatsApp message.
     *
     * @param string $content
     */
    public function content($content = '')
    {
        $this->content = $content;
    }
}
