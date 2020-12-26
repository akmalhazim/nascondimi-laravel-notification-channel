<?php

namespace NotificationChannels\Nascondimi;

abstract class NascondimiMessage
{
    /**
     * Message constructor.
     *
     * @param string $message
     */
    public function __construct($message = '')
    {
        $this->message($message);
    }

    /** @var array Params payload. */
    protected $payload = [];

    /** @var string Endpoint URI */
    protected $endpoint;

    /**
     * Recipient's phone number.
     *
     * @param string $phoneNumber
     *
     * @return $this
     */
    public function to($phoneNumber)
    {
        $this->payload['phone_no'] = $phoneNumber;
    }

    /**
     * Determine if the phone number is not given.
     *
     * @return bool
     */
    public function toNotGiven()
    {
        return !isset($this->payload['phone_no']);
    }

    /**
     * Additional options to pass to send method.
     *
     * @param array $options
     *
     * @return $this
     */
    public function options($options = [])
    {
        $this->payload = \array_merge($this->payload, $options);

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
     * Returns params payload.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->payload;
    }

    /**
     * Set endpoint.
     *
     * @param string $endpoint
     *
     * @return $this
     */
    public function endpoint(string $endpoint)
    {
        $this->endpoint = sprintf('/%s', ltrim($endpoint, '/'));

        return $this;
    }

    /**
     * Get endpoint.
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Get recipient phone number.
     *
     * @return string
     */
    public function getPhone()
    {
        return isset($this->payload['phone_no']) ? $this->payload['phone_no'] : null;
    }
}
