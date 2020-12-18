<?php

namespace NotificationChannels\Nascondimi;

use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Http\Exception\ClientException;
use GuzzleHttp\Http\Exception\ServerException;
use GuzzleHttp\RequestOptions;
use NotificationChannels\Nascondimi\Exceptions\CouldNotSendNotification;
use NotificationChannels\Nascondimi\Exceptions\InvalidConfigException;

class Nascondimi
{
    /** @var HttpClient HTTP Client */
    protected $http;

    /** @var string|null Nascondimi API Token. */
    protected $token;

    /** @var string|null API endpoint URI. */
    protected $endpoint;

    public function __construct($token = null, HttpClient $httpClient = null, $endpoint = null)
    {
        $this->token = $token;
        $this->http = $httpClient;
        $this->endpoint($endpoint);
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
    public function send(array $params): void
    {
        if (blank($this->token)) {
            throw InvalidConfigException::missingToken();
        }

        try {
            $url = sprintf('%s/send_message', $this->endpoint);
            $response = $this->http->post($url, [
                RequestOptions::JSON => [
                    'key'      => $this->token,
                    'phone_no' => $params['phone_number'],
                    'message'  => $params['message'],
                ],
            ]);
            $message = $response->getBody()->getContents();
            if ($message != 'Success') {
                throw CouldNotSendNotification::clientError($message);
            }
        } catch (ServerException $e) {
            throw CouldNotSendNotification::serverError($e->getResponse()->getBody()->getContents());
        } catch (ClientException $e) {
            throw CouldNotSendNotification::clientError($e->getResponse()->getBody()->getContents());
        } catch (CouldNotSendNotification $e) {
            throw $e;
        } catch (Exception $e) {
            throw CouldNotSendNotification::unknownError($e->getResponse()->getBody()->getContents());
        }
    }

    /**
     * Set the endpoint.
     *
     * @param string $endpoint
     *
     * @return $this
     */
    public function endpoint(string $endpoint): self
    {
        $this->endpoint = rtrim($endpoint, '/');

        return $this;
    }
}
