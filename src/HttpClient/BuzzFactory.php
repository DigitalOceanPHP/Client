<?php

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
use Buzz\Client\ClientInterface;
use Buzz\Client\Curl;
use Buzz\Client\FileGetContents;

/**
 * @author Graham Campbell <graham@alt-three.com>
 */
class BuzzFactory implements FactoryInterface
{
    /**
     * @param string|null $token
     *
     * @return BuzzHttpClient
     */
    public function create(string $token = null)
    {
        $browser = new Browser(self::makeTransport());

        if ($token !== null) {
            $browser->addMiddleware(new BuzzOAuthMiddleware($token));
        }

        return new BuzzHttpClient($browser);
    }

    /**
     * @return ClientInterface
     */
    private static function makeTransport()
    {
        return function_exists('curl_exec') ? new Curl() : new FileGetContents();
    }
}
