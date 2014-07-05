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

use Buzz\Browser;
use Buzz\Client\Curl;
use Buzz\Listener\ListenerInterface;
use Buzz\Message\Response;

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
     * @param string            $accessToken
     * @param Browser           $browser (optional)
     * @param ListenerInterface $listener (optional)
     */
    public function __construct($accessToken, Browser $browser = null, ListenerInterface $listener = null)
    {
        $this->browser = $browser ?: new Browser(new Curl);
        $this->browser->addListener($listener ?: new BuzzOAuthListener($accessToken));
    }

    /**
     * {@inheritdoc}
     */
    public function get($url)
    {
        $response = $this->browser->get($url);

        if (!$response->isSuccessful()) {
            $this->handleResponse($response);
        }

        return $response->getContent();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($url, $headers = array())
    {
        $response = $this->browser->delete($url, $headers);

        if (!$response->isSuccessful()) {
            $this->handleResponse($response);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function put($url, $headers = array(), $content = '')
    {
        $response = $this->browser->put($url, $headers, $content);

        if (!$response->isSuccessful()) {
            $this->handleResponse($response);
        }

        return $response->getContent();
    }


    /**
     * {@inheritdoc}
     */
    public function post($url, $headers = array(), $content = '')
    {
        $response = $this->browser->post($url, $headers, $content);

        if (!$response->isSuccessful()) {
            $this->handleResponse($response);
        }

        return $response->getContent();
    }

    /**
     * {@inheritdoc}
     */
    public function getLatestResponseHeaders()
    {
        if (null === $response = $this->browser->getLastResponse()) {
            return null;
        }

        return array(
            'reset'     => (integer) $response->getHeader('RateLimit-Reset'),
            'remaining' => (integer) $response->getHeader('RateLimit-Remaining'),
            'limit'     => (integer) $response->getHeader('RateLimit-Limit'),
        );
    }

    /**
     * @param  Response          $response
     * @throws \RuntimeException
     */
    protected function handleResponse(Response $response)
    {
        $content = json_decode($response->getContent());

        throw new \RuntimeException(sprintf('[%d] %s', $response->getStatusCode(), $content->message));
    }
}
