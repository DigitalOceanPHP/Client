<?php

namespace DigitalOceanV2\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\ResponseInterface;
use DigitalOceanV2\Exception\ExceptionInterface;

class GuzzleAdapter extends AbstractAdapter implements AdapterInterface
{
    /**
     * @var Client
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
     * @param ExceptionInterface $exception (optional)
     */
    public function __construct($accessToken, ExceptionInterface $exception = null)
    {
        $this->accessToken = $accessToken;
        $this->client      = new Client();
        $this->exception   = $exception;

        $this->client->getEmitter()->on(
            'before',
            function (BeforeEvent $event) use ($accessToken) {
                $event->getRequest()->setHeader('Authorization', sprintf("Bearer %s", $accessToken));
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function get($url)
    {
        try {
            $this->response = $this->client->get($url);
            return $this->response->getBody();
        } catch (\Exception $e) {
            throw $this->handleResponse($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete($url, $headers = array())
    {
        try {
            $this->response = $this->client->delete($url, array('headers' => $headers));
            return $this->response->getBody();
        } catch (\Exception $e) {
            throw $this->handleResponse($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function put($url, $headers = array(), $content = "")
    {
        try {
            $this->response = $this->client->put($url, array('headers' => $headers, 'body' => json_decode($content, true)));
            return $this->response->getBody();
        } catch (\Exception $e) {
            throw $this->handleResponse($e);
        }
    }


    /**
     * {@inheritdoc}
     */
    public function post($url, $headers = array(), $content = "")
    {
        try {
            $this->response = $this->client->post($url, array('headers' => $headers, 'body' => json_decode($content, true)));
            return $this->response->getBody();
        } catch (\Exception $e) {
            throw $this->handleResponse($e);
        }
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
     * Create exception
     *
     * @param \Exception $exception
     *
     * @return \Exception
     */
    protected function handleResponse(\Exception $exception)
    {
        if ($exception instanceof RequestException) {
            $response = (string) $exception->getResponse()->getBody();
            $code     = $exception->getResponse()->getStatusCode();
        } else {
            $response = $exception->getMessage();
            $code     = $exception->getCode();
        }

        if ($this->exception) {
            return $this->exception->create($response, $code);
        } else {
            $response = sprintf('[%d] %s', $code, $response);
            return new \RuntimeException($response, $code);
        }
    }
}
