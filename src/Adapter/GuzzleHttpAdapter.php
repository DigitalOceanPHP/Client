<?php

namespace DigitalOceanV2\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;

/**
 * @author Marcos Sigueros <alrik11es@gmail.com>
 * @author Chris Fidao <fideloper@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
class GuzzleHttpAdapter implements AdapterInterface
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Response|ResponseInterface
     */
    protected $response;

    /**
     * @param string               $token
     * @param ClientInterface|null $client
     */
    public function __construct($token, ClientInterface $client = null)
    {
        if (version_compare(ClientInterface::VERSION, '6') === 1) {
            $this->client = $client ?: new Client(['headers' => ['Authorization' => sprintf('Bearer %s', $token)]]);
        } else {
            $this->client = $client ?: new Client();

            $this->client->setDefaultOption('headers/Authorization', sprintf('Bearer %s', $token));

            $this->client->getEmitter()->on('complete', function (CompleteEvent $event) {
                $this->handleResponse($event);
                $event->stopPropagation();
            });
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($url)
    {
        try {
            $this->response = $this->client->get($url);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->handleException();
        }

        return $this->response->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($url)
    {
        try {
            $this->response = $this->client->delete($url);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->handleException();
        }

        return $this->response->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function put($url, $content = '')
    {
        $options = [];

        $options[is_array($content) ? 'json' : 'body'] = $content;

        try {
            $this->response = $this->client->put($url, $options);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->handleException();
        }

        return $this->response->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function post($url, $content = '')
    {
        $options = [];

        $options[is_array($content) ? 'json' : 'body'] = $content;

        try {
            $this->response = $this->client->post($url, $options);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->handleException();
        }

        return $this->response->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function getLatestResponseHeaders()
    {
        if (null === $this->response) {
            return;
        }

        return [
            'reset' => (int) (string) $this->response->getHeader('RateLimit-Reset'),
            'remaining' => (int) (string) $this->response->getHeader('RateLimit-Remaining'),
            'limit' => (int) (string) $this->response->getHeader('RateLimit-Limit'),
        ];
    }

    /**
     * @param CompleteEvent $event
     *
     * @throws \RuntimeException
     */
    protected function handleResponse(CompleteEvent $event)
    {
        $this->response = $event->getResponse();

        if ($this->response->getStatusCode() >= 200 && $this->response->getStatusCode() <= 299) {
            return;
        }

        $this->handleException();
    }

    /**
     * @throws \RuntimeException
     */
    protected function handleException()
    {
        $body = (string) $this->response->getBody();
        $code = (int) $this->response->getStatusCode();

        $content = json_decode($body);

        throw new \RuntimeException(sprintf('[%d] %s (%s)', $code, $content->message, $content->id), $code);
    }
}
