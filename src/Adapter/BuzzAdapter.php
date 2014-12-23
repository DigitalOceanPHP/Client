<?php

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Adapter;

use Buzz\Browser;
use Buzz\Client\Curl;
use Buzz\Listener\ListenerInterface;
use Buzz\Message\Response;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
class BuzzAdapter extends AbstractAdapter implements AdapterInterface
{
    /**
     * @var Browser
     */
    protected $browser;

    /**
     * @var ExceptionInterface
     */
    protected $exception;

    /**
     * @param string             $accessToken
     * @param Browser            $browser     (optional)
     * @param ListenerInterface  $listener    (optional)
     * @param ExceptionInterface $exception   (optional)
     */
    public function __construct($accessToken, Browser $browser = null, ListenerInterface $listener = null, ExceptionInterface $exception = null)
    {
        $this->browser = $browser ?: new Browser(new Curl());
        $this->browser->addListener($listener ?: new BuzzOAuthListener($accessToken));
        $this->exception = $exception;
    }

    /**
     * {@inheritdoc}
     */
    public function get($url)
    {
        $response = $this->browser->get($url);

        if (!$response->isSuccessful()) {
            throw $this->handleResponse($response);
        }

        return $response->getContent();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($url, array $headers = array())
    {
        $response = $this->browser->delete($url, $headers);

        if (!$response->isSuccessful()) {
            throw $this->handleResponse($response);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function put($url, array $headers = array(), $content = '')
    {
        $response = $this->browser->put($url, $headers, $content);

        if (!$response->isSuccessful()) {
            throw $this->handleResponse($response);
        }

        return $response->getContent();
    }

    /**
     * {@inheritdoc}
     */
    public function post($url, array $headers = array(), $content = '')
    {
        $response = $this->browser->post($url, $headers, $content);

        if (!$response->isSuccessful()) {
            throw $this->handleResponse($response);
        }

        return $response->getContent();
    }

    /**
     * {@inheritdoc}
     */
    public function getLatestResponseHeaders()
    {
        if (null === $response = $this->browser->getLastResponse()) {
            return;
        }

        return array(
            'reset'     => (int) $response->getHeader('RateLimit-Reset'),
            'remaining' => (int) $response->getHeader('RateLimit-Remaining'),
            'limit'     => (int) $response->getHeader('RateLimit-Limit'),
        );
    }

    /**
     * @param Response $response
     *
     * @return \Exception
     */
    protected function handleResponse(Response $response)
    {
        if ($this->exception) {
            return $this->exception->create($response->getContent(), $response->getStatusCode());
        }

        $content = json_decode($response->getContent());

        return new \RuntimeException(
            sprintf('[%d] %s (%s)', $response->getStatusCode(), $content->message, $content->id)
        );
    }
}
