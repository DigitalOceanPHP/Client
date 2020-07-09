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

use DigitalOceanV2\Entity\Meta;
use DigitalOceanV2\HttpClient\HttpClientInterface;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 * @author Graham Campbell <graham@alt-three.com>
 */
abstract class AbstractApi
{
    /**
     * @var string
     */
    public const ENDPOINT = 'https://api.digitalocean.com/v2';

    /**
     * @var HttpClientInterface
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var Meta|null
     */
    protected $meta;

    /**
     * @param HttpClientInterface $httpClient
     * @param string|null         $endpoint
     *
     * @return void
     */
    public function __construct(HttpClientInterface $httpClient, $endpoint = null)
    {
        $this->httpClient = $httpClient;
        $this->endpoint = $endpoint ?? static::ENDPOINT;
    }

    /**
     * @param \stdClass $data
     *
     * @return Meta|null
     */
    protected function extractMeta(\StdClass $data)
    {
        if (isset($data->meta)) {
            $this->meta = new Meta($data->meta);
        }

        return $this->meta;
    }

    /**
     * @return Meta|null
     */
    public function getMeta()
    {
        return $this->meta;
    }
}
