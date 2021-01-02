<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOcean API library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\HttpClient\Message;

use DigitalOceanV2\Exception\RuntimeException;
use DigitalOceanV2\HttpClient\Util\JsonObject;
use Psr\Http\Message\ResponseInterface;
use stdClass;

/**
 * This is the response mediator class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
final class ResponseMediator
{
    /**
     * The content type header.
     *
     * @var string
     */
    public const CONTENT_TYPE_HEADER = 'Content-Type';

    /**
     * The JSON content type identifier.
     *
     * @var string
     */
    public const JSON_CONTENT_TYPE = 'application/json';

    /**
     * @param ResponseInterface $response
     *
     * @throws RuntimeException
     *
     * @return stdClass
     */
    public static function getContent(ResponseInterface $response): stdClass
    {
        if (204 === $response->getStatusCode()) {
            return JsonObject::empty();
        }

        $body = (string) $response->getBody();

        if ('' === $body) {
            return JsonObject::empty();
        }

        if (0 !== \strpos(self::getHeader($response, self::CONTENT_TYPE_HEADER) ?? '', self::JSON_CONTENT_TYPE)) {
            throw new RuntimeException(\sprintf('The content type was not %s.', self::JSON_CONTENT_TYPE));
        }

        return JsonObject::decode($body);
    }

    /**
     * Get the error message from the response if present.
     *
     * @param ResponseInterface $response
     *
     * @return string|null
     */
    public static function getErrorMessage(ResponseInterface $response): ?string
    {
        try {
            $content = self::getContent($response);
        } catch (RuntimeException $e) {
            return null;
        }

        return isset($content->message) && \is_string($content->message) ? $content->message : null;
    }

    /**
     * Get the pagination data from the response.
     *
     * @param ResponseInterface $response
     *
     * @return array<string,string>
     */
    public static function getPagination(ResponseInterface $response): array
    {
        try {
            $content = self::getContent($response);
        } catch (RuntimeException $e) {
            return [];
        }

        if (!isset($content->links->pages) || !\is_object($content->links->pages)) {
            return [];
        }

        /** array<string,string> */
        return \array_filter(\get_object_vars($content->links->pages));
    }

    /**
     * Get the rate limit data from the response.
     *
     * @param ResponseInterface $response
     *
     * @return array<string,int>
     */
    public static function getRateLimit(ResponseInterface $response): array
    {
        $reset = self::getHeader($response, 'RateLimit-Reset');
        $remaining = self::getHeader($response, 'RateLimit-Remaining');
        $limit = self::getHeader($response, 'RateLimit-Limit');

        if (null === $reset || null === $remaining || null === $limit) {
            return [];
        }

        return [
            'reset' => (int) $reset,
            'remaining' => (int) $remaining,
            'limit' => (int) $limit,
        ];
    }

    /**
     * @param ResponseInterface $response
     * @param string            $name
     *
     * @return string|null
     */
    private static function getHeader(ResponseInterface $response, string $name): ?string
    {
        $headers = $response->getHeader($name);

        return \array_shift($headers);
    }
}
