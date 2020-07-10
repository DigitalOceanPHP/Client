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

/**
 * @author Graham Campbell <graham@alt-three.com>
 */
interface HttpMethodsClientInterface
{
    /**
     * @param string               $uri
     * @param array<string,string> $headers
     *
     * @throws ExceptionInterface
     *
     * @return Response
     */
    public function get(string $uri, array $headers);

    /**
     * @param string               $uri
     * @param array<string,string> $headers
     * @param string               $body
     *
     * @throws ExceptionInterface
     *
     * @return Response
     */
    public function post(string $uri, array $headers, string $body);

    /**
     * @param string               $uri
     * @param array<string,string> $headers
     * @param string               $body
     *
     * @throws ExceptionInterface
     *
     * @return Response
     */
    public function put(string $uri, array $headers, string $body);

    /**
     * @param string               $uri
     * @param array<string,string> $headers
     * @param string               $body
     *
     * @throws ExceptionInterface
     *
     * @return Response
     */
    public function delete(string $uri, array $headers, string $body);

    /**
     * @return Response|null
     */
    public function getLastResponse();
}
