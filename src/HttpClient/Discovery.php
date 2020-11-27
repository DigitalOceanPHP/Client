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

use DigitalOceanV2\Exception\DiscoveryFailedException;
use GuzzleHttp\Client;

/**
 * @author Graham Campbell <graham@alt-three.com>
 */
class Discovery
{
    /**
     * Automatically find an HTTP client if possible.
     *
     * @throws DiscoveryFailedException
     *
     * @return HttpClientInterface
     */
    public static function find()
    {
        if (null !== ($httpClient = self::discoverGuzzle())) {
            return $httpClient;
        }

        if (\PHP_MAJOR_VERSION > 7) {
            throw new DiscoveryFailedException('Unable to find a suitable HTTP client. Please make sure a suitable version of Guzzle is installed: "guzzlehttp/guzzle:^7.2". Older versions of Guzzle are not supported on PHP 8.');
        }

        throw new DiscoveryFailedException('Unable to find a suitable HTTP client. Please make sure a suitable version of Guzzle is installed: "guzzlehttp/guzzle:^6.3.1" or "guzzlehttp/guzzle:^7.0".');
    }

    /**
     * @return HttpClientInterface|null
     */
    private static function discoverGuzzle()
    {
        // ensure Guzzle is installed version is at least 4.0.0
        if (!\class_exists(Client::class)) {
            return null;
        }

        $version = self::getGuzzleVersion();

        // ensure Guzzle version is at least 6.3.1 or at least 7 on PHP 8
        if (null === $version || \version_compare($version, '6.3.1') < 0 || (\version_compare($version, '7') < 0 && \PHP_MAJOR_VERSION > 7)) {
            return null;
        }

        return new GuzzleHttpClient();
    }

    /**
     * @return string|null
     */
    private static function getGuzzleVersion()
    {
        return self::readConstant('GuzzleHttp\ClientInterface::VERSION') ?? self::readConstant('GuzzleHttp\ClientInterface::MAJOR_VERSION');
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    private static function readConstant(string $name)
    {
        if (!\defined($name)) {
            return null;
        }

        $value = \constant($name);

        return \is_scalar($value) ? (string) $value : null;
    }
}
