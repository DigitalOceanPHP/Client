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

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Client;
use DigitalOceanV2\Exception\ExceptionInterface;
use DigitalOceanV2\Exception\RuntimeException;
use DigitalOceanV2\HttpClient\HttpMethodsClientInterface;
use DigitalOceanV2\HttpClient\Message\Response;
use DigitalOceanV2\HttpClient\Message\ResponseMediator;
use DigitalOceanV2\HttpClient\Util\JsonObject;
use DigitalOceanV2\HttpClient\Util\QueryStringBuilder;
use stdClass;
use ValueError;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 * @author Graham Campbell <graham@alt-three.com>
 */
abstract class AbstractApi implements ApiInterface
{
    /**
     * The URI prefix.
     *
     * @var string
     */
    private const URI_PREFIX = '/v2/';

    /**
     * The client instance.
     *
     * @var Client
     */
    private $client;

    /**
     * The per page parameter.
     *
     * @var int|null
     */
    private $perPage;

    /**
     * The page parameter.
     *
     * @var int|null
     */
    private $page;

    /**
     * @param Client   $client
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return void
     */
    public final function __construct(Client $client, int $perPage = null, int $page = null)
    {
        if (null !== $perPage && ($perPage < 1 || $perPage > 200)) {
            throw new ValueError(sprintf('%s::__construct(): Argument #2 ($perPage) must be between 1 and 200, or null', self::class));
        }

        if (null !== $page && $page < 1) {
            throw new ValueError(sprintf('%s::__construct(): Argument #3 ($page) must be greater than or equal to 1, or null', self::class));
        }

        $this->client = $client;
        $this->perPage = $perPage;
        $this->page = $page;
    }

    /**
     * Create a new instance with the given page parameter.
     *
     * This must be an integer between 1 and 200.
     *
     * @param int|null $perPage
     *
     * @return static
     */
    public function perPage(?int $perPage)
    {
        return new static($this->client, $perPage, $this->page);
    }

    /**
     * Create a new instance with the given page parameter.
     *
     * This must be an integer greater than or equal to 1.
     *
     * @param int|null $page
     *
     * @return static
     */
    public function page(?int $page)
    {
        return new static($this->client, $this->perPage, $page);
    }

    /**
     * Send a GET request with query params.
     *
     * @param string               $uri
     * @param array                $params
     * @param array<string,string> $headers
     *
     * @throws ExceptionInterface
     *
     * @return stdClass
     */
    protected function get(string $uri, array $params = [], array $headers = [])
    {
        if (null !== $this->perPage) {
            $params = array_merge(['per_page' => $this->perPage], $params);
        }

        if (null !== $this->page) {
            $params = array_merge(['page' => $this->page], $params);
        }

        $response = $this->client->getHttpClient()->get(self::prepareUri($uri, $params), $headers);

        return self::getContent($response);
    }

    /**
     * Send a POST request with JSON-encoded params.
     *
     * @param string               $uri
     * @param array                $params
     * @param array<string,string> $headers
     *
     * @throws ExceptionInterface
     *
     * @return stdClass
     */
    protected function post(string $uri, array $params = [], array $headers = [])
    {
        $body = self::prepareJsonBody($params);

        if (null !== $body) {
            $headers = self::addJsonContentType($headers);
        }

        $response = $this->client->getHttpClient()->post(self::prepareUri($uri), $headers, $body ?? '');

        return self::getContent($response);
    }

    /**
     * Send a PUT request with JSON-encoded params.
     *
     * @param string               $uri
     * @param array                $params
     * @param array<string,string> $headers
     *
     * @throws ExceptionInterface
     *
     * @return stdClass
     */
    protected function put(string $uri, array $params = [], array $headers = [])
    {
        $body = self::prepareJsonBody($params);

        if (null !== $body) {
            $headers = self::addJsonContentType($headers);
        }

        $response = $this->client->getHttpClient()->put(self::prepareUri($uri), $headers, $body ?? '');

        return self::getContent($response);
    }

    /**
     * Send a DELETE request with JSON-encoded params.
     *
     * @param string               $uri
     * @param array                $params
     * @param array<string,string> $headers
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    protected function delete(string $uri, array $params = [], array $headers = [])
    {
        $body = self::prepareJsonBody($params);

        if (null !== $body) {
            $headers = self::addJsonContentType($headers);
        }

        $this->client->getHttpClient()->delete(self::prepareUri($uri), $headers, $body ?? '');
    }

    /**
     * Prepare the request URI.
     *
     * @param string $uri
     * @param array  $query
     *
     * @return string
     */
    private static function prepareUri(string $uri, array $query = [])
    {
        return sprintf('%s%s%s', self::URI_PREFIX, $uri, QueryStringBuilder::build($query));
    }

    /**
     * Prepare the request JSON body.
     *
     * @param array $params
     *
     * @return string|null
     */
    private static function prepareJsonBody(array $params)
    {
        if (0 === count($params)) {
            return null;
        }

        return JsonObject::encode($params);
    }

    /**
     * Add the JSON content type to the headers if one is not already present.
     *
     * @param array<string,string> $headers
     *
     * @return array<string,string>
     */
    private static function addJsonContentType(array $headers)
    {
        return array_merge(['Content-Type' => ResponseMediator::JSON_CONTENT_TYPE], $headers);
    }

    /**
     * @param Response $response
     *
     * @return stdClass
     */
    private static function getContent(Response $response)
    {
        $content = ResponseMediator::getContent($response);

        if (null === $content) {
            throw new RuntimeException('No content was provided.');
        }

        return $content;
    }
}
