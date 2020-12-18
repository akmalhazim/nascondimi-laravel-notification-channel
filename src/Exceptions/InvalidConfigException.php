<?php

namespace NotificationChannels\Nascondimi\Exceptions;

use Exception;

class InvalidConfigException extends Exception
{
    /**
     * Thrown when token is not provided or invalid.
     *
     * @return static
     */
    public static function missingToken(): self
    {
        return new static('Please provide a valid token');
    }
}
