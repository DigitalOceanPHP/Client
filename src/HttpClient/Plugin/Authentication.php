<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOcean API library.
 *
 * (c) Antoine Kirk <contact@sbin.dk>
 * (c) Graham Campbell <hello@gjcampbell.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * A plugin to add authentication to the request.
 *
 * @internal
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
final class Authentication implements Plugin
{
    /**
     * The authorization header.
     */
    private readonly string $header;

    /**
     * The base URL.
     */
    private UriInterface $url;

    public function __construct(#[\SensitiveParameter] string $token, UriInterface $url)
    {
        $this->header = \sprintf('Bearer %s', $token);
        $this->url = $url;
    }

    public function withUrl(UriInterface $url): self
    {
        $plugin = clone $this;
        $plugin->url = $url;

        return $plugin;
    }

    /**
     * Handle the request and return the response coming from the next callable.
     *
     * @param callable(RequestInterface): Promise<ResponseInterface> $next
     * @param callable(RequestInterface): Promise<ResponseInterface> $first
     *
     * @return \Http\Promise\Promise<ResponseInterface>
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        if (self::isSameOrigin($request->getUri(), $this->url)) {
            $request = $request->withHeader('Authorization', $this->header);
        } elseif ($request->getHeaderLine('Authorization') === $this->header) {
            // a redirected request already carries it
            $request = $request->withoutHeader('Authorization');
        }

        return $next($request);
    }

    private static function isSameOrigin(UriInterface $a, UriInterface $b): bool
    {
        return 0 === \strcasecmp($a->getScheme(), $b->getScheme())
            && 0 === \strcasecmp($a->getHost(), $b->getHost())
            && self::getPort($a) === self::getPort($b);
    }

    private static function getPort(UriInterface $uri): ?int
    {
        return $uri->getPort() ?? ['http' => 80, 'https' => 443][\strtolower($uri->getScheme())] ?? null;
    }
}
