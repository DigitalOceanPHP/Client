<?php

/**
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Adapter;

use DigitalOceanV2\Exception\ExceptionInterface;
use Guzzle\Common\Event;
use Guzzle\Http\Client;
use Guzzle\Http\Message\Response;

/**
 * @author liverbool <nukboon@gmail.com>
 */
class GuzzleAdapter extends AbstractAdapter implements AdapterInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var ExceptionInterface
     */
    protected $exception;

    /**
     * @param string             $accessToken
     * @param ExceptionInterface $exception (optional)
     */
    public function __construct($accessToken, ExceptionInterface $exception = null)
    {
        $that              = $this;
        $this->client      = new Client;
        $this->accessToken = $accessToken;
        $this->exception   = $exception;

        $this->client

            // Set default Bearer header for all request
            ->setDefaultOption('headers/Authorization', sprintf('Bearer %s', $this->accessToken))

            // Subscribe completed request event
            ->setDefaultOption('events/request.complete', function (Event $event) use ($that) {
                $that->handleResponse($event);
                $event->stopPropagation();
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function get($url)
    {
        $this->response = $this->client->get($url)->send();

        return $this->response->getBody(true);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($url, $headers = array())
    {
        $this->response = $this->client->delete($url, $headers)->send();

        return $this->response->getBody(true);
    }

    /**
     * {@inheritdoc}
     */
    public function put($url, $headers = array(), $content = '')
    {
        $this->response = $this->client->put($url, $headers, json_decode($content, true))->send();

        return $this->response->getBody(true);
    }

    /**
     * {@inheritdoc}
     */
    public function post($url, $headers = array(), $content = '')
    {
        $this->response = $this->client->post($url, $headers, json_decode($content, true))->send();

        return $this->response->getBody(true);
    }

    /**
     * {@inheritdoc}
     */
    public function getLatestResponseHeaders()
    {
        if (null === $response = $this->response) {
            return null;
        }

        return array(
            'reset'     => (integer) $response->getHeader('RateLimit-Reset'),
            'remaining' => (integer) $response->getHeader('RateLimit-Remaining'),
            'limit'     => (integer) $response->getHeader('RateLimit-Limit'),
        );
    }

    /**
     * @param  Event                                $event
     * @throws \RuntimeException|ExceptionInterface
     */
    protected function handleResponse(Event $event)
    {
        $this->response = $event['response'];

        if ($this->response->isSuccessful()) {
            return;
        }

        $body = $this->response->getBody(true);
        $code = $this->response->getStatusCode();

        if ($this->exception) {
            throw $this->exception->create($body, $code);
        }

        $content = json_decode($body);

        throw new \RuntimeException(sprintf('[%d] %s (%s)', $code, $content->message, $content->id), $code);
    }
}
