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
use GuzzleHttp\Client;
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

        return (string) $this->response->getBody();
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

        return (string) $this->response->getBody();
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

        return (string) $this->response->getBody();
    }

    /**
     * @param string $url
     * @param array|string $content
     *
     * @throws HttpException
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

        return (string) $this->response->getBody();
    }

    /**
     * @return array|null
     */
    public function getLatestResponseHeaders()
    {
        if ($this->response === null) {
            return null;
        }

        return [
            'reset' => (int) (string) $this->response->getHeader('RateLimit-Reset'),
            'remaining' => (int) (string) $this->response->getHeader('RateLimit-Remaining'),
            'limit' => (int) (string) $this->response->getHeader('RateLimit-Limit'),
        ];
    }

    /**
     * @param ResponseInterface $response
     *
     * @throws HttpException
     */
    private static function handleError(ResponseInterface $response)
    {
        $body = (string) $response->getBody();
        $code = (int) $response->getStatusCode();

        $content = json_decode($body);

        throw new HttpException(isset($content->message) ? $content->message : 'Request not processed.', $code);
    }
}
