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

use DigitalOceanV2\Exception\ExceptionInterface;
use DigitalOceanV2\HttpClient\Message\Response;
use DigitalOceanV2\HttpClient\Message\ResponseMediator;
use DigitalOceanV2\HttpClient\Util\ExceptionFactory;

/**
 * @author Graham Campbell <graham@alt-three.com>
 */
final class HttpMethodsClient implements HttpMethodsClientInterface
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var array<string,string>
     */
    private $defaultHeaders;

    /**
     * @var Response|null
     */
    private $lastResponse;

    /**
     * @param HttpClientInterface  $httpClient
     * @param string               $baseUrl
     * @param array<string,string> $defaultHeaders
     *
     * @return void
     */
    public function __construct(HttpClientInterface $httpClient, string $baseUrl, array $defaultHeaders = [])
    {
        $this->httpClient = $httpClient;
        $this->baseUrl = $baseUrl;
        $this->defaultHeaders = $defaultHeaders;
    }

    /**
     * @param string               $uri
     * @param array<string,string> $headers
     *
     * @throws ExceptionInterface
     *
     * @return Response
     */
    public function get(string $uri, array $headers)
    {
        return $this->send('GET', $uri, $headers, '');
    }

    /**
     * @param string               $uri
     * @param array<string,string> $headers
     * @param string               $body
     *
     * @throws ExceptionInterface
     *
     * @return Response
     */
    public function post(string $uri, array $headers, string $body)
    {
        return $this->send('POST', $uri, $headers, $body);
    }

    /**
     * @param string               $uri
     * @param array<string,string> $headers
     * @param string               $body
     *
     * @throws ExceptionInterface
     *
     * @return Response
     */
    public function put(string $uri, array $headers, string $body)
    {
        return $this->send('PUT', $uri, $headers, $body);
    }

    /**
     * @param string               $uri
     * @param array<string,string> $headers
     * @param string               $body
     *
     * @throws ExceptionInterface
     *
     * @return Response
     */
    public function delete(string $uri, array $headers, string $body)
    {
        return $this->send('DELETE', $uri, $headers, $body);
    }

    /**
     * @param string               $method
     * @param string               $uri
     * @param array<string,string> $headers
     * @param string               $body
     *
     * @throws ExceptionInterface
     *
     * @return Response
     */
    private function send(string $method, string $uri, array $headers, string $body)
    {
        $url = sprintf('%s%s', $this->baseUrl, $uri);

        $headers = array_merge($this->defaultHeaders, $headers);

        $this->lastResponse = $response = $this->httpClient->sendRequest($method, $url, $headers, $body);

        if ($response->getStatusCode() < 300) {
            return $response;
        }

        $message = ResponseMediator::getErrorMessage($response) ?? $response->getReasonPhrase();

        throw ExceptionFactory::create($response->getStatusCode(), $message);
    }

    /**
     * @return Response|null
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }
}
