<?php

namespace NotificationChannels\Nascondimi\Exceptions;

use Exception;

class CouldNotSendNotification extends Exception
{
    /**
     * Thrown when Nascondimi returned with server error.
     *
     * @param string $exception
     *
     * @return static
     */
    public static function serverError(string $exception = ''): self
    {
        return new static("Nascondimi server error: `$exception`");
    }

    /**
     * Thrown when Nascondimi returned with client error.
     *
     * @param $exception
     *
     * @return static
     */
    public static function clientError(string $exception = ''): self
    {
        return new static("Nascondimi client error: `{$exception}`");
    }

    /**
     * Thrown when Nascondimi or application returned with unknown error.
     *
     * @param $exception
     *
     * @return static
     */
    public static function unknownError(string $exception = ''): self
    {
        return new static("Unknown error: `$exception`");
    }
}
