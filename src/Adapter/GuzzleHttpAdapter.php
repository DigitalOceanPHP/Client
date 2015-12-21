<?php

namespace DigitalOceanV2\Adapter;

use DigitalOceanV2\Exception\ExceptionInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;

class GuzzleHttpAdapter extends AbstractAdapter implements AdapterInterface
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var ExceptionInterface
     */
    protected $exception;

    /**
     * @param string             $accessToken
     * @param ClientInterface    $client      (optional)
     * @param ExceptionInterface $exception   (optional)
     */
    public function __construct($accessToken, ClientInterface $client = null, ExceptionInterface $exception = null)
    {
        if (version_compare(ClientInterface::VERSION, '6') === 1) {
            $this->client = $client ?: new Client(['headers' => ['Authorization' =>  sprintf('Bearer %s', $accessToken)]]);
        } else {
            $this->client = $client ?: new Client();

            $this->client->setDefaultOption('headers/Authorization', sprintf('Bearer %s', $accessToken));

            $this->client->getEmitter()->on('complete', function (CompleteEvent $e) {
                $this->handleResponse($e);
                $e->stopPropagation();
            });
        }

        $this->exception = $exception;
    }

    /**
     * {@inheritdoc}
     */
    public function get($url)
    {
        try {
            $this->response = $this->client->get($url);
        } catch (RequestException $e) {
            throw $this->handleResponse($e->getResponse());
        }

        return $this->response->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($url, array $headers = array())
    {
        try {
            $options = array('headers' => $headers);
            $this->response = $this->client->delete($url, $options);
        } catch (RequestException $e) {
            throw $this->handleResponse($e->getResponse());
        }

        return $this->response->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function put($url, array $headers = array(), $content = '')
    {
        try {
            $options = version_compare(ClientInterface::VERSION, '6') === 1 && ($json = json_decode($content, true)) ?
                array('headers' => $headers, 'json' => $json) :
                array('headers' => $headers, 'body' => $content);

            $request = $this->client->put($url, $options);
            $this->response = $request;
        } catch (RequestException $e) {
            throw $this->handleResponse($e->getResponse());
        }

        return $this->response->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function post($url, array $headers = array(), $content = '')
    {
        try {
            $options = version_compare(ClientInterface::VERSION, '6') === 1 && ($json = json_decode($content, true)) ?
                array('headers' => $headers, 'json' => $json) :
                array('headers' => $headers, 'body' => $content);

            $this->response = $this->client->post($url, $options);
        } catch (RequestException $e) {
            throw $this->handleResponse($e->getResponse());
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

        return array(
            'reset'     => (int) (string) $this->response->getHeader('RateLimit-Reset'),
            'remaining' => (int) (string) $this->response->getHeader('RateLimit-Remaining'),
            'limit'     => (int) (string) $this->response->getHeader('RateLimit-Limit'),
        );
    }

    /**
     * @param CompleteEvent $event
     *
     * @throws \RuntimeException|ExceptionInterface
     */
    protected function handleResponse(CompleteEvent $event)
    {
        $this->response = $event->getResponse();

        if ($this->response->getStatusCode() >= 200 && $this->response->getStatusCode() <= 299) {
            return;
        }

        $body = $this->response->getBody();
        $code = $this->response->getStatusCode();

        if ($this->exception) {
            throw $this->exception->create($body, $code);
        }

        $content = $this->response->json();

        throw new \RuntimeException(sprintf('[%d] %s (%s)', $code, $content->message, $content->id), $code);
    }

    /**
     * @param Response $response
     *
     * @throws \RuntimeException|ExceptionInterface
     */
    protected function handleException(Response $response)
    {
        $body = (string) $response->getBody();
        $code = $response->getStatusCode();

        if ($this->exception) {
            return $this->exception->create($body, $code);
        }

        $content = json_decode($body);

        throw new \RuntimeException(sprintf('[%d] %s (%s)', $code, $content->message, $content->id), $code);
    }
}
