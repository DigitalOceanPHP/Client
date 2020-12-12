<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOcean API library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Client;
use DigitalOceanV2\Exception\ExceptionInterface;
use DigitalOceanV2\HttpClient\Message\ResponseMediator;
use DigitalOceanV2\HttpClient\Util\JsonObject;
use DigitalOceanV2\HttpClient\Util\QueryStringBuilder;
use stdClass;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 * @author Graham Campbell <graham@alt-three.com>
 */
abstract class AbstractApi
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
     * Create a new API instance.
     *
     * @param Client $client
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
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
    protected function get(string $uri, array $params = [], array $headers = []): stdClass
    {
        if (null !== $this->perPage && !isset($params['per_page'])) {
            $params = \array_merge(['per_page' => $this->perPage], $params);
        }

        if (null !== $this->page && !isset($params['page'])) {
            $params = \array_merge(['page' => $this->page], $params);
        }

        $response = $this->client->getHttpClient()->get(self::prepareUri($uri, $params), $headers);

        return ResponseMediator::getContent($response);
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
    protected function post(string $uri, array $params = [], array $headers = []): stdClass
    {
        $body = self::prepareJsonBody($params);

        if (null !== $body) {
            $headers = self::addJsonContentType($headers);
        }

        $response = $this->client->getHttpClient()->post(self::prepareUri($uri), $headers, $body ?? '');

        return ResponseMediator::getContent($response);
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
    protected function put(string $uri, array $params = [], array $headers = []): stdClass
    {
        $body = self::prepareJsonBody($params);

        if (null !== $body) {
            $headers = self::addJsonContentType($headers);
        }

        $response = $this->client->getHttpClient()->put(self::prepareUri($uri), $headers, $body ?? '');

        return ResponseMediator::getContent($response);
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
    protected function delete(string $uri, array $params = [], array $headers = []): void
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
    private static function prepareUri(string $uri, array $query = []): string
    {
        return \sprintf('%s%s%s', self::URI_PREFIX, $uri, QueryStringBuilder::build($query));
    }

    /**
     * Prepare the request JSON body.
     *
     * @param array $params
     *
     * @return string|null
     */
    private static function prepareJsonBody(array $params): ?string
    {
        if (0 === \count($params)) {
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
    private static function addJsonContentType(array $headers): array
    {
        return \array_merge([ResponseMediator::CONTENT_TYPE_HEADER => ResponseMediator::JSON_CONTENT_TYPE], $headers);
    }
}
