<?php

namespace NotificationChannels\Nascondimi\Exceptions;

use Exception;

class CouldNotSendNotification extends Exception
{
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

    public static function otherError(): self
    {
        return new static('Nascondimi other error');
    }

    /**
     * Thrown when the phone is offline.
     *
     * @return static
     */
    public static function phoneOffline(): self
    {
        return new static('Phone is offline');
    }

    /**
     * Thrown when the recipient phone number is not found.
     * Application should not retry.
     *
     * @return static
     */
    public static function numberNotFound($phone = null): self
    {
        return new static('Number not found'.$phone ? ' - '.$phone : '');
    }

    /**
     * Thrown when server returned wait too long response.
     *
     * @return static
     */
    public static function waitTooLong()
    {
        return new static('Nascondimi wait too long');
    }
}
