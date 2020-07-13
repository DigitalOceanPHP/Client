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

use Buzz\Browser;
use Buzz\Exception\RequestException;
use Buzz\ListenerLogger\Listener;
use DigitalOceanV2\Exception\DiscoveryFailedException;
use GuzzleHttp\Client;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

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

        if (null !== ($httpClient = self::discoverBuzz())) {
            return $httpClient;
        }

        throw new DiscoveryFailedException('Unable to find a suitable HTTP client. Please make sure one of the following is installed: "guzzlehttp/guzzle:^6.3.1", "guzzlehttp/guzzle:^7.0", or "kriswallsmith/buzz:^0.16".');
    }

    /**
     * @return HttpClientInterface|null
     */
    private static function discoverGuzzle()
    {
        // ensure Guzzle is installed version is at least 4.0.0
        if (!class_exists(Client::class)) {
            return null;
        }

        $version = self::getGuzzleVersion();

        // ensure Guzzle version is at least 6.3.1
        if (null === $version || version_compare($version, '6.3.1') < 0) {
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
        if (!defined($name)) {
            return null;
        }

        $value = constant($name);

        return is_scalar($value) ? (string) $value : null;
    }

    /**
     * @return HttpClientInterface|null
     */
    private static function discoverBuzz()
    {
        // ensure Buzz is installed and version is less than 0.17.0
        if (!class_exists(Browser::class) || !class_exists(Listener::class)) {
            return null;
        }

        $param = self::getFirstParam(RequestException::class, 'setRequest');

        // ensure Buzz version is at least 0.16.0
        if (null === $param || $param->hasType()) {
            return null;
        }

        return new BuzzHttpClient();
    }

    /**
     * @param string $class
     * @param string $method
     *
     * @return ReflectionParameter|null
     */
    private static function getFirstParam(string $class, string $method)
    {
        try {
            $method = (new ReflectionClass(RequestException::class))->getMethod('setRequest');

            return $method->getParameters()[0] ?? null;
        } catch (ReflectionException $e) {
            return null;
        }
    }
}
