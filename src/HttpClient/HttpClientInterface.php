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

use DigitalOceanV2\HttpClient\Message\Response;

/**
 * @author Graham Campbell <graham@alt-three.com>
 */
interface HttpClientInterface
{
    /**
     * @param string               $method
     * @param string               $url
     * @param array<string,string> $headers
     * @param string               $body
     *
     * @return Response
     */
    public function sendRequest(string $method, string $url, array $headers, string $body);
}
