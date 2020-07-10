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

namespace DigitalOceanV2\HttpClient\Util;

use DigitalOceanV2\Exception\ApiLimitExceededException;
use DigitalOceanV2\Exception\ErrorException;
use DigitalOceanV2\Exception\RuntimeException;
use DigitalOceanV2\Exception\ValidationFailedException;

/**
 * @internal
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
final class ExceptionFactory
{
    /**
     * Create an exception from a status code and error message.
     *
     * @param int    $status
     * @param string $message
     *
     * @return ErrorException|RuntimeException
     */
    public static function create(int $status, string $message)
    {
        if (400 === $status || 422 === $status) {
            return new ValidationFailedException($message, $status);
        }

        if (429 === $status) {
            return new ApiLimitExceededException($message, $status);
        }

        return new RuntimeException($message, $status);
    }
}
