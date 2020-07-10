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

use DigitalOceanV2\Exception\RuntimeException;
use DigitalOceanV2\HttpClient\Message\Response;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Marcos Sigueros <alrik11es@gmail.com>
 * @author Chris Fidao <fideloper@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
final class GuzzleHttpClient implements HttpClientInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param ClientInterface $client
     *
     * @return void
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
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
            $response = $this->client->request($method, $url, ['body' => $body, 'headers' => $headers]);
        } catch (GuzzleException $e) {
            throw new RuntimeException('An HTTP transport error occured.', 0, $e);
        }

        return new Response(
            $response->getStatusCode(),
            $response->getReasonPhrase(),
            self::getHeaders($response),
            (string) $response->getBody()
        );
    }

    /**
     * @param ResponseInterface $response
     *
     * @return array<string,string[]>
     */
    private static function getHeaders(ResponseInterface $response)
    {
        return [
            'Content-Type' => $response->getHeader('Content-Type'),
            'RateLimit-Reset' => $response->getHeader('RateLimit-Reset'),
            'RateLimit-Remaining' => $response->getHeader('RateLimit-Remaining'),
            'RateLimit-Limit' => $response->getHeader('RateLimit-Limit'),
        ];
    }
}
