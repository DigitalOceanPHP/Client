<?php

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Exception;

/**
 * @author liverbool <nukboon@gmail.com>
 */
interface ExceptionInterface
{
    /**
     * Create an exception.
     *
     * @param string     $message
     * @param int        $code     (optional)
     * @param \Exception $previous (optional)
     *
     * @return ExceptionInterface
     */
    public static function create($message, $code = 0, \Exception $previous = null);
}
