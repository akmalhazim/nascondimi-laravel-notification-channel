<?php

namespace NotificationChannels\Nascondimi;

use NotificationChannels\Nascondimi\Contracts\Uploadable;
use NotificationChannels\Nascondimi\Exceptions\FileException;

class NascondimiWhatsappFile extends NascondimiMessage implements Uploadable
{
    protected $file;

    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->endpoint('send_image');
    }

    /**
     * Set attachment file.
     *
     * @param string $file
     *
     * @return $this
     */
    public function file(string $file)
    {
        $isLocalFile = $this->isReadableFile($file);
        if (!$isLocalFile) {
            throw FileException::fileNotFound();
        }

        $filename = \sprintf('%s%s', 'WHATSAPP', now()->format('Y-m-d_h:m:s'));

        $this->payload['filename'] = $filename;
        $this->file = file_get_contents($file);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function hasFile(): bool
    {
        return isset($this->payload['filename']) && $this->file !== null;
    }

    /**
     * @inheritdoc
     */
    public function getFileContents(): string
    {
        return $this->file;
    }

    /**
     * @inheritdoc
     */
    public function getFileName(): string
    {
        return isset($this->payload['filename']) ? $this->payload['filename'] : null;
    }

    /**
     * Determine if it's a regular and readable file.
     *
     * @param string $file
     *
     * @return bool
     */
    protected function isReadableFile(string $file)
    {
        return \is_file($file) && \is_readable($file);
    }
}
