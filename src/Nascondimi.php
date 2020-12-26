<?php

namespace NotificationChannels\Nascondimi;

use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\RequestOptions;
use NotificationChannels\Nascondimi\Exceptions\CouldNotSendNotification;
use NotificationChannels\Nascondimi\Exceptions\InvalidConfigException;

class Nascondimi
{
    /** @var HttpClient HTTP Client */
    protected $http;

    /** @var string|null Nascondimi API Token. */
    protected $token;

    /** @var string|null API base URI. */
    protected $baseUri;

    public function __construct($token = null, HttpClient $httpClient = null, $baseUri = null)
    {
        $this->token = $token;
        $this->http = $httpClient;
        $this->baseUri($baseUri);
    }

    /**
     * Send text message.
     *
     * <code>
     * $params = [
     *   'phone_number' => '',
     *   'message' => '',
     * ];
     * </code>
     *
     * @param array $params
     *
     * @throws CouldNotSendNotification|InvalidConfigException
     *
     * @return void
     */
    public function send(string $endpoint, array $params)
    {
        if (blank($this->token)) {
            throw InvalidConfigException::missingToken();
        }

        try {
            $response = $this->http->post(
                $this->buildUri($endpoint),
                [
                    RequestOptions::JSON => array_merge($params, [
                        'key' => $this->token,
                    ]),
                ]
            );
        } catch (Exception $e) {
            throw $e;
        }

        return $this->evaluateResponse(
            $response->getBody()->getContents()
        );
    }

    /**
     * Upload file to server.
     *
     * @param array $params
     *
     * @return null
     */
    public function uploadFile(array $params)
    {
        if (blank($this->token)) {
            throw InvalidConfigException::missingToken();
        }

        try {
            $response = $this->http->post(
                $this->buildUri('/file_upload', $this->token),
                [
                    'multipart' => [
                        [
                            'name'     => 'file',
                            'contents' => $params['file'],
                            'filename' => $params['filename'],
                        ],
                    ],
                ]
            );
        } catch (Exception $e) {
            throw $e;
        }

        return $this->evaluateResponse(
            $response->getBody()->getContents()
        );
    }

    /**
     * Evaluate service provider response and throw error if necessary.
     *
     * @param string $response
     *
     * @return null
     */
    private function evaluateResponse($response)
    {
        $response = \strtolower($response);
        switch ($response) {
            case 'success':
                return null;
            case 'phone_offline':
                throw CouldNotSendNotification::phoneOffline();
            case 'other_error':
                throw CouldNotSendNotification::otherError();
            default:
                // test against broken error response
                if (strpos($response, 'number not found')) {
                    throw CouldNotSendNotification::numberNotFound();
                } elseif (strpos($response, 'wait_too_long')) {
                    throw CouldNotSendNotification::waitTooLong();
                }
        }
    }

    /**
     * Build the endpoint URI from the given endpoint.
     *
     * @param string      $endpoint
     * @param string|null $optionalToken
     *
     * @return string
     */
    private function buildUri($endpoint, $optionalToken = null)
    {
        $uri = $this->baseUri.$endpoint;
        if ($optionalToken) {
            $uri .= '/'.$optionalToken;
        }

        return $uri;
    }

    /**
     * Set the base URI.
     *
     * @param string $baseUri
     *
     * @return $this
     */
    public function baseUri(string $baseUri): self
    {
        $this->baseUri = rtrim($baseUri, '/');

        return $this;
    }
}
