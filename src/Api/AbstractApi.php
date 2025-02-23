<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOcean API library.
 *
 * (c) Antoine Kirk <contact@sbin.dk>
 * (c) Graham Campbell <hello@gjcampbell.co.uk>
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
 * @author Antoine Kirk <contact@sbin.dk>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
abstract class AbstractApi
{
    /**
     * The URI prefix.
     *
     * @var string
     */
    private const URI_PREFIX = '/v2/';

    private readonly Client $client;

    private ?int $perPage;

    private ?int $page;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->perPage = null;
        $this->page = null;
    }

    /**
     * Send a GET request with query params.
     *
     * @param array<string,string> $headers
     *
     * @throws ExceptionInterface
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
     * @param array<string,string> $headers
     *
     * @throws ExceptionInterface
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
     * @param array<string,string> $headers
     *
     * @throws ExceptionInterface
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
     * @param array<string,string> $headers
     * @param array<string,string> $queryParams
     *
     * @throws ExceptionInterface
     */
    protected function delete(string $uri, array $params = [], array $headers = [], array $queryParams = []): void
    {
        $body = self::prepareJsonBody($params);

        if (null !== $body) {
            $headers = self::addJsonContentType($headers);
        }

        $this->client->getHttpClient()->delete(self::prepareUri($uri, $queryParams), $headers, $body ?? '');
    }

    /**
     * Prepare the request URI.
     */
    private static function prepareUri(string $uri, array $query = []): string
    {
        return \sprintf('%s%s%s', self::URI_PREFIX, $uri, QueryStringBuilder::build($query));
    }

    /**
     * Prepare the request JSON body.
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
