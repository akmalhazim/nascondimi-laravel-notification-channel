<?php

namespace NotificationChannels\Nascondimi\Exceptions;

use Exception;

class FileException extends Exception
{
    /**
     * Thrown when the file path is not found.
     *
     * @return static
     */
    public static function fileNotFound()
    {
        return new static('File not found');
    }
}
