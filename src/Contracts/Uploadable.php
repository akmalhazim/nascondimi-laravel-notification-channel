<?php

namespace NotificationChannels\Nascondimi\Contracts;

interface Uploadable
{
    /**
     * Get the file path in our application.
     *
     * @return string
     */
    public function getFileContents(): string;

    /**
     * Get file name.
     *
     * @return string
     */
    public function getFileName(): string;

    /**
     * Check if file is given or not.
     *
     * @return bool
     */
    public function hasFile(): bool;
}
