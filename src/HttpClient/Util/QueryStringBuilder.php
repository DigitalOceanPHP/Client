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

namespace DigitalOceanV2\HttpClient\Util;

/**
 * The is the URI builder helper class.
 *
 * @internal
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
final class QueryStringBuilder
{
    /**
     * Encode a query as a query string according to RFC 3986.
     *
     * @param array $query
     *
     * @return string
     */
    public static function build(array $query): string
    {
        if (0 === \count($query)) {
            return '';
        }

        return \sprintf('?%s', \http_build_query($query, '', '&', \PHP_QUERY_RFC3986));
    }
}
