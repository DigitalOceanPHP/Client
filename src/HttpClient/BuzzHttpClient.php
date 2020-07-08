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

        return $response->getContent();
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

        return $response->getContent();
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

        return $response->getContent();
    }

    /**
     * @param string $url
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

        return $response->getContent();
    }

    /**
     * @return array|null
     */
    public function getLatestResponseHeaders()
    {
        $response = $this->browser->getLastResponse();

        if ($response === null) {
            return null;
        }

        return [
            'reset' => (int) $response->getHeader('RateLimit-Reset'),
            'remaining' => (int) $response->getHeader('RateLimit-Remaining'),
            'limit' => (int) $response->getHeader('RateLimit-Limit'),
        ];
    }

    /**
     * @param Response $response
     *
     * @throws HttpException
     *
     * @return void
     */
    private static function handleResponse(Response $response)
    {
        if ($response->getStatusCode() === 200) {
            return;
        }

        self::handleError($response);
    }

    /**
     * @param Response $response
     *
     * @throws HttpException
     *
     * @return void
     */
    private static function handleError(Response $response)
    {
        $body = $response->getContent();
        $code = $response->getStatusCode();

        $content = json_decode($body);

        throw new HttpException(isset($content->message) ? $content->message : 'Request not processed.', $code);
    }
}
