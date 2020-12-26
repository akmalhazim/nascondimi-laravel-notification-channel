<?php

namespace NotificationChannels\Nascondimi;

use NotificationChannels\Nascondimi\Traits\HasMessage;

class NascondimiWhatsappMessage extends NascondimiMessage
{
    use HasMessage;

    /**
     * Message constructor.
     *
     * @param string $content
     */
    public function __construct($content = '')
    {
        $this->endpoint('send_message');
    }
}
