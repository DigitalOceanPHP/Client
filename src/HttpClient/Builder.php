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

/**
 * @author Graham Campbell <graham@alt-three.com>
 */
final class Builder
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var string|null
     */
    private $baseUrl;

    /**
     * @var array<string,string>
     */
    private $defaultHeaders;

    /**
     * @var HttpMethodsClientInterface|null
     */
    private $httpMethodsClient;

    /**
     * @param HttpClientInterface|null $httpClient
     *
     * @return void
     */
    public function __construct(HttpClientInterface $httpClient = null)
    {
        $this->defaultHeaders = [];
        $this->httpClient = $httpClient ?? Discovery::find();
    }

    /**
     * @param string|null $agent
     *
     * @return void
     */
    public function setUserAgent(string $agent = null)
    {
        if (null === $agent) {
            unset($this->defaultHeaders['User-Agent']);
        } else {
            $this->defaultHeaders['User-Agent'] = $agent;
        }

        $this->httpMethodsClient = null;
    }

    /**
     * @param string|null $token
     *
     * @return void
     */
    public function setAuthToken(string $token = null)
    {
        if (null === $token) {
            unset($this->defaultHeaders['Authorization']);
        } else {
            $this->defaultHeaders['Authorization'] = sprintf('Bearer %s', $token);
        }

        $this->httpMethodsClient = null;
    }

    /**
     * @param string|null $url
     *
     * @return void
     */
    public function setBaseUrl(string $url = null)
    {
        $this->baseUrl = $url;
        $this->httpMethodsClient = null;
    }

    /**
     * @return HttpMethodsClientInterface
     */
    public function getHttpClient()
    {
        if (null === $this->httpMethodsClient) {
            $this->httpMethodsClient = new HttpMethodsClient(
                $this->httpClient,
                $this->baseUrl ?? '',
                $this->defaultHeaders
            );
        }

        return $this->httpMethodsClient;
    }
}
