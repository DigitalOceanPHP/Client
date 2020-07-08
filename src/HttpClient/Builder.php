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
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var string|null
     */
    private $authToken;

    /**
     * @var HttpClientInterface|null
     */
    private $httpClient;

    /**
     * @param FactoryInterface|null $factory
     *
     * @return void
     */
    public function __construct(FactoryInterface $factory = null)
    {
        $this->factory = $factory ?? FactoryDiscovery::find();
    }

    /**
     * @param string|null $token
     *
     * @return void
     */
    public function setAuthToken(string $token = null)
    {
        $this->authToken = $token;
        $this->httpClient = null;
    }

    /**
     * @return HttpClientInterface
     */
    public function getHttpClient()
    {
        if (null === $this->httpClient) {
            $this->httpClient = $this->factory->create($this->authToken);
        }

        return $this->httpClient;
    }
}
