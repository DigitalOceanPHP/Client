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
use DigitalOceanV2\Entity\Meta;
use DigitalOceanV2\Exception\ExceptionInterface;
use DigitalOceanV2\Exception\RuntimeException;
use DigitalOceanV2\HttpClient\HttpMethodsClientInterface;
use DigitalOceanV2\HttpClient\Message\Response;
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
     * The HTTP methods client.
     *
     * @var HttpMethodsClientInterface
     */
    private $httpClient;

    /**
     * @param Client $client
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->httpClient = $client->getHttpClient();
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
        $response = $this->httpClient->get(self::prepareUri($uri, $params), $headers);

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

        $response = $this->httpClient->post(self::prepareUri($uri), $headers, $body ?? '');

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

        $response = $this->httpClient->put(self::prepareUri($uri), $headers, $body ?? '');

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

        $this->httpClient->delete(self::prepareUri($uri), $headers, $body ?? '');
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
