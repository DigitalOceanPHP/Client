<?php

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2;

/**
 * Transform snake_case to camelCase.
 *
 * @param string $property
 *
 * @return string
 */
function convert_to_camel_case($property)
{
    return lcfirst(preg_replace_callback(
        '/(^|_)([a-z])/',
        function ($match) {
            return strtoupper($match[2]);
        },
        $property
    ));
}

/**
 * Transform camelCase to snake_case.
 *
 * @param string $property
 *
 * @return string
 */
function convert_to_snake_case($property)
{
    return strtolower(preg_replace('/([A-Z])/', '_$1', $property));
}

/**
 * Returns a string representation of a boolean.
 *
 * @param bool $value
 *
 * @return string
 */
function bool_to_string($value)
{
    return $value ? 'true' : 'false';
}
