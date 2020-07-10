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
use Buzz\Exception\ExceptionInterface as BuzzException;
use Buzz\Message\Response as BuzzResponse;
use DigitalOceanV2\Exception\RuntimeException;
use DigitalOceanV2\HttpClient\Message\RateLimit;
use DigitalOceanV2\HttpClient\Message\Response;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 * @author Graham Campbell <graham@alt-three.com>
 */
final class BuzzHttpClient implements HttpClientInterface
{
    /**
     * @var Browser
     */
    private $browser;

    /**
     * @param Browser $browser
     *
     * @return void
     */
    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * @param string               $method
     * @param string               $url
     * @param array<string,string> $headers
     * @param string               $body
     *
     * @return Response
     */
    public function sendRequest(string $method, string $url, array $headers, string $body)
    {
        try {
            /** @var BuzzResponse */
            $response = $this->browser->call($url, $method, $headers, $body);
        } catch (BuzzException $e) {
            throw new RuntimeException('An HTTP transport error occured.', 0, $e);
        }

        return new Response(
            $response->getStatusCode() ?? 500,
            $response->getReasonPhrase() ?? '',
            self::getHeaders($response),
            $response->getContent()
        );
    }

    /**
     * @param BuzzResponse $response
     *
     * @return array<string,string[]>
     */
    private static function getHeaders(BuzzResponse $response)
    {
        return [
            'Content-Type' => $response->getHeader('Content-Type', false) ?? [],
            'RateLimit-Reset' => $response->getHeader('RateLimit-Reset', false) ?? [],
            'RateLimit-Remaining' => $response->getHeader('RateLimit-Remaining', false) ?? [],
            'RateLimit-Limit' => $response->getHeader('RateLimit-Limit', false) ?? [],
        ];
    }
}
