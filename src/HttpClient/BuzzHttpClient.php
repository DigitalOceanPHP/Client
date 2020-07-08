<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\HttpClient;

use Buzz\Browser;
use Buzz\Message\Response;
use DigitalOceanV2\Exception\HttpException;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 * @author Graham Campbell <graham@alt-three.com>
 */
class BuzzHttpClient implements HttpClientInterface
{
    /**
     * @var Browser
     */
    private $browser;

    /**
     * @param Browser $browser
     *
     * @return void
     */
    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * @param string $url
     *
     * @throws HttpException
     *
     * @return string
     */
    public function get(string $url)
    {
        $response = $this->browser->get($url);

        self::handleResponse($response);

        return self::getResponseBody($response);
    }

    /**
     * @param string       $url
     * @param array|string $content
     *
     * @throws HttpException
     *
     * @return string
     */
    public function post(string $url, $content = '')
    {
        $headers = [];

        if (is_array($content)) {
            $content = json_encode($content);
            $headers[] = 'Content-Type: application/json';
        }

        $response = $this->browser->post($url, $headers, $content);

        self::handleResponse($response);

        return self::getResponseBody($response);
    }

    /**
     * @param string       $url
     * @param array|string $content
     *
     * @throws HttpException
     *
     * @return string
     */
    public function put(string $url, $content = '')
    {
        $headers = [];

        if (is_array($content)) {
            $content = json_encode($content);
            $headers[] = 'Content-Type: application/json';
        }

        $response = $this->browser->put($url, $headers, $content);

        self::handleResponse($response);

        return self::getResponseBody($response);
    }

    /**
     * @param string       $url
     * @param array|string $content
     *
     * @throws HttpException
     *
     * @return string
     */
    public function delete(string $url, $content = '')
    {
        $headers = [];

        if (is_array($content)) {
            $content = json_encode($content);
            $headers[] = 'Content-Type: application/json';
        }

        $response = $this->browser->delete($url, $headers, $content);

        self::handleResponse($response);

        return self::getResponseBody($response);
    }

    /**
     * @return array<string,int>|null
     */
    public function getLatestResponseHeaders()
    {
        /** @var Response|null */
        $response = $this->browser->getLastResponse();

        if (null === $response) {
            return null;
        }

        $reset = self::getHeader($response, 'RateLimit-Reset');
        $remaining = self::getHeader($response, 'RateLimit-Remaining');
        $limit = self::getHeader($response, 'RateLimit-Limit');

        if (null === $reset || null === $remaining || null === $limit) {
            return null;
        }

        return [
            'reset' => (int) $reset,
            'remaining' => (int) $remaining,
            'limit' => (int) $limit,
        ];
    }

    /**
     * @param Response|null $response
     *
     * @return string
     */
    private static function getResponseBody(?Response $response)
    {
        return null === $response ? '' : (string) $response->getContent();
    }

    /**
     * @param Response $response
     * @param string   $name
     *
     * @return string|null
     */
    private static function getHeader(Response $response, string $name)
    {
        /** @var string[]|null */
        $headers = $response->getHeader($name, false);

        return null === $headers ? null : array_shift($headers);
    }

    /**
     * @param Response|null $response
     *
     * @throws HttpException
     *
     * @return void
     */
    private static function handleResponse(?Response $response)
    {
        if (null !== $response && 200 === $response->getStatusCode()) {
            return;
        }

        self::handleError($response);
    }

    /**
     * @param Response|null $response
     *
     * @throws HttpException
     *
     * @return void
     */
    private static function handleError(?Response $response)
    {
        if (null === $response) {
            throw new HttpException('An HTTP transport error occured.');
        }

        $body = self::getResponseBody($response);
        $code = $response->getStatusCode();

        $content = json_decode($body);

        throw new HttpException(isset($content->message) ? $content->message : 'Request not processed.', $code);
    }
}
