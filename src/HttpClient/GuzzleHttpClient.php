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

use DigitalOceanV2\Exception\HttpException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Marcos Sigueros <alrik11es@gmail.com>
 * @author Chris Fidao <fideloper@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
class GuzzleHttpClient implements HttpClientInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var ResponseInterface|null
     */
    private $response;

    /**
     * @param ClientInterface $client
     *
     * @return void
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
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
        return $this->send('GET', $url, '');
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
        return $this->send('POST', $url, $content);
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
        return $this->send('PUT', $url, $content);
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
        return $this->send('DELETE', $url, $content);
    }

    /**
     * @param string       $method
     * @param string       $url
     * @param array|string $content
     *
     * @throws HttpException
     *
     * @return string
     */
    public function send(string $method, string $url, $content)
    {
        $options = [];

        $options[is_array($content) ? 'json' : 'body'] = $content;

        try {
            $this->response = $response = $this->client->request($method, $url, $options);
        } catch (GuzzleException $e) {
            $this->response = $response = self::getResponseFromException($e);

            if (null === $response || $response->getStatusCode() < 300) {
                throw new HttpException('An HTTP transport error occured.', 0, $e);
            }

            throw new HttpException(self::getExceptionMessageFor($response), $response->getStatusCode(), $e);
        }

        return (string) $response->getBody();
    }

    /**
     * @param GuzzleException $e
     *
     * @return ResponseInterface|null
     */
    private static function getResponseFromException(GuzzleException $e)
    {
        return method_exists($e, 'getResponse') ? $e->getResponse() : null;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return string
     */
    private static function getExceptionMessageFor(ResponseInterface $response)
    {
        $content = json_decode((string) $response->getBody());

        return isset($content->message) ? $content->message : 'Request not processed.';
    }

    /**
     * @return array<string,int>|null
     */
    public function getLatestResponseHeaders()
    {
        if (null === $this->response) {
            return null;
        }

        $reset = self::getHeader($this->response, 'RateLimit-Reset');
        $remaining = self::getHeader($this->response, 'RateLimit-Remaining');
        $limit = self::getHeader($this->response, 'RateLimit-Limit');

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
     * @param ResponseInterface $response
     * @param string            $name
     *
     * @return string|null
     */
    private static function getHeader(ResponseInterface $response, string $name)
    {
        /** @var string[] */
        $headers = $response->getHeader($name);

        return array_shift($headers);
    }
}
