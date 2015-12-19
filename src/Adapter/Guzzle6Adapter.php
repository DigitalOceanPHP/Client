<?php

namespace DigitalOceanV2\Adapter;

use DigitalOceanV2\Exception\ExceptionInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

class Guzzle6Adapter extends AbstractAdapter implements AdapterInterface
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
        $this->client    = $client ?: new \GuzzleHttp\Client(['headers' => [
           'Authorization' =>  sprintf('Bearer %s', $accessToken)
        ]]);
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
            throw $this->handleResponse( $e->getResponse() );
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
            throw $this->handleResponse( $e->getResponse() );
        }

        return $this->response->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function put($url, array $headers = array(), $content = '')
    {
        try {
            $options = array('headers' => $headers, 'body' => $content);
            $this->response = $this->client->put($url, $options);
        } catch (RequestException $e) {
            throw $this->handleResponse( $e->getResponse() );
        }

        return $this->response->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function post($url, array $headers = array(), $content = '')
    {
        try {
            $options = array('headers' => $headers, 'body' => $content);
            $this->response = $this->client->post($url, $options);
        } catch (RequestException $e) {
            throw $this->handleResponse( $e->getResponse() );
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
     * @param Response $response
     * @return ExceptionInterface|\RuntimeException
     */
    protected function handleResponse(Response $response)
    {
        $body = $this->response->getBody();
        $code = $this->response->getStatusCode();

        if ($this->exception) {
            return $this->exception->create($body, $code);
        }

        $content = $this->response->json();

        return new \RuntimeException(sprintf('[%d] %s (%s)', $code, $content->message, $content->id), $code);
    }
}
