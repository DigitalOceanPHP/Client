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
use GuzzleHttp\Exception\RequestException;
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
        try {
            $this->response = $this->client->request('GET', $url);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            self::handleError($this->response);
        }

        return self::getResponseBody($this->response);
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
        $options = [];

        $options[is_array($content) ? 'json' : 'body'] = $content;

        try {
            $this->response = $this->client->request('POST', $url, $options);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            self::handleError($this->response);
        }

        return self::getResponseBody($this->response);
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
        $options = [];

        $options[is_array($content) ? 'json' : 'body'] = $content;

        try {
            $this->response = $this->client->request('PUT', $url, $options);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            self::handleError($this->response);
        }

        return self::getResponseBody($this->response);
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
        $options = [];

        $options[is_array($content) ? 'json' : 'body'] = $content;

        try {
            $this->response = $this->client->request('DELETE', $url, $options);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            self::handleError($this->response);
        }

        return self::getResponseBody($this->response);
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
     * @param ResponseInterface|null $response
     *
     * @return string
     */
    private static function getResponseBody(?ResponseInterface $response)
    {
        return $response === null ? '' : (string) $response->getBody();
    }

    /**
     * @param ResponseInterface $response
     * @param string            $name
     *
     * @return string|null
     */
    public static function getHeader(ResponseInterface $response, string $name)
    {
        /** @var string[] */
        $headers = $response->getHeader($name);

        return array_shift($headers);
    }

    /**
     * @param ResponseInterface|null $response
     *
     * @throws HttpException
     *
     * @return void
     */
    private static function handleError(?ResponseInterface $response)
    {
        if ($response === null) {
            throw new HttpException('An HTTP transport error occured.');
        }

        $body = self::getResponseBody($response);
        $code = $response->getStatusCode();

        $content = json_decode($body);

        throw new HttpException(isset($content->message) ? $content->message : 'Request not processed.', $code);
    }
}
