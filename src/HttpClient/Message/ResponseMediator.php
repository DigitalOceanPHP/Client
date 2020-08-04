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

use DigitalOceanV2\Exception\RuntimeException;
use DigitalOceanV2\HttpClient\Util\JsonObject;
use stdClass;

/**
 * This is the response mediator class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
final class ResponseMediator
{
    /**
     * The JSON content type identifier.
     *
     * @var string
     */
    public const JSON_CONTENT_TYPE = 'application/json';
    public const CSV_CONTENT_TYPE = 'text/csv';

    /**
     * @param Response $response
     *
     * @return stdClass|null
     * @throws RuntimeException
     *
     */
    public static function getContent(Response $response)
    {
        if (204 === $response->getStatusCode()) {
            return null;
        }

        $body = $response->getBody();

        if ('' === $body) {
            return null;
        }
        if (0 === \strpos(self::getHeader($response, 'Content-Type') ?? '', self::CSV_CONTENT_TYPE)) {
            return $body;
        }

        if (0 !== \strpos(self::getHeader($response, 'Content-Type') ?? '', self::JSON_CONTENT_TYPE)) {
            throw new RuntimeException(\sprintf('The content type was not %s.', self::JSON_CONTENT_TYPE));
        }

        return JsonObject::decode($body);
    }

    /**
     * Get the error message from the response if present.
     *
     * @param Response $response
     *
     * @return string|null
     */
    public static function getErrorMessage(Response $response)
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
     * @param Response $response
     *
     * @return array<string,string>
     */
    public static function getPagination(Response $response)
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
     * @param Response $response
     *
     * @return array<string,int>
     */
    public static function getRateLimit(Response $response)
    {
        $reset = self::getHeader($response, 'RateLimit-Reset');
        $remaining = self::getHeader($response, 'RateLimit-Remaining');
        $limit = self::getHeader($response, 'RateLimit-Limit');

        if (null === $reset || null === $remaining || null === $limit) {
            return [];
        }

        return [
            'reset'     => (int)$reset,
            'remaining' => (int)$remaining,
            'limit'     => (int)$limit,
        ];
    }

    /**
     * @param Response $response
     * @param string $name
     *
     * @return string|null
     */
    private static function getHeader(Response $response, string $name)
    {
        $headers = $response->getHeaders()[$name] ?? [];

        return \array_shift($headers);
    }
}
