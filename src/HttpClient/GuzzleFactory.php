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

use GuzzleHttp\Client;

/**
 * @author Graham Campbell <graham@alt-three.com>
 */
class GuzzleFactory implements FactoryInterface
{
    /**
     * @param string|null $token
     *
     * @return GuzzleHttpClient
     */
    public function create(string $token = null)
    {
        return new GuzzleHttpClient(
            new Client(self::getOptions($token))
        );
    }

    /**
     * @param string|null $token
     *
     * @return array
     */
    private static function getOptions(?string $token)
    {
        return null === $token ? [] : ['headers' => ['Authorization' => sprintf('Bearer %s', $token)]];
    }
}
