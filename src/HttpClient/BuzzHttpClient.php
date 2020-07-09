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
use Buzz\Exception\ExceptionInterface as BuzzException;
use Buzz\Message\Response;
use DigitalOceanV2\Exception\ExceptionInterface;
use DigitalOceanV2\Exception\RuntimeException;
use DigitalOceanV2\HttpClient\Util\JsonObject;

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
     * @throws ExceptionInterface
     *
     * @return string
     */
    public function get(string $url)
    {
        return $this->send('GET', $url, '');
    }

    /**
     * @param string       $url
     * @param array|string $content
     *
     * @throws ExceptionInterface
     *
     * @return string
     */
    public function post(string $url, $content = '')
    {
        return $this->send('POST', $url, $content);
    }

    /**
     * @param string       $url
     * @param array|string $content
     *
     * @throws ExceptionInterface
     *
     * @return string
     */
    public function put(string $url, $content = '')
    {
        return $this->send('PUT', $url, $content);
    }

    /**
     * @param string       $url
     * @param array|string $content
     *
     * @throws ExceptionInterface
     *
     * @return string
     */
    public function delete(string $url, $content = '')
    {
        return $this->send('DELETE', $url, $content);
    }

    /**
     * @param string       $method
     * @param string       $url
     * @param array|string $content
     *
     * @throws ExceptionInterface
     *
     * @return string
     */
    private function send(string $method, string $url, $content)
    {
        $headers = [];

        if (is_array($content)) {
            $content = JsonObject::encode($content);
            $headers[] = 'Content-Type: application/json';
        }

        try {
            /** @var Response */
            $response = $this->browser->call($url, $method, $headers, $content);
        } catch (BuzzException $e) {
            throw new RuntimeException('An HTTP transport error occured.', 0, $e);
        }

        if ($response->getStatusCode() < 300) {
            return $response->getContent();
        }

        throw ExceptionFactory::create($response->getStatusCode(), self::getExceptionMessageFor($response));
    }

    /**
     * @param Response $response
     *
     * @return string
     */
    private static function getExceptionMessageFor(Response $response)
    {
        try {
            $content = JsonObject::decode($response->getContent());
        } catch (RuntimeException $e) {
            return 'Request not processed.';
        }

        return isset($content->message) && is_string($content->message) ? $content->message : 'Request not processed.';
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
}
