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

namespace DigitalOceanV2\HttpClient\Message;

/**
 * @author Graham Campbell <graham@alt-three.com>
 */
final class Response
{
    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string
     */
    private $reasonPhrase;

    /**
     * @var array<string,string[]>
     */
    private $headers;

    /**
     * @var string
     */
    private $body;

    /**
     * @param int                    $statusCode
     * @param string                 $reasonPhrase
     * @param string                 $body
     * @param array<string,string[]> $headers
     *
     * @return void
     */
    public function __construct(int $statusCode, string $reasonPhrase, array $headers, string $body)
    {
        $this->statusCode = $statusCode;
        $this->reasonPhrase = $reasonPhrase;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getReasonPhrase()
    {
        return $this->reasonPhrase;
    }

    /**
     * @return array<string,string[]>
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
}
