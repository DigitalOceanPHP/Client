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

namespace DigitalOceanV2\Entity;

use DigitalOceanV2\Exception\RuntimeException;

/**
 * @author Antoine Kirk <contact@sbin.dk>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
abstract class AbstractEntity
{
    /**
     * @param object|array|null $parameters
     *
     * @return void
     */
    public function __construct($parameters = null)
    {
        if (null === $parameters) {
            return;
        }

        if (\is_object($parameters)) {
            $parameters = \get_object_vars($parameters);
        }

        $this->build($parameters);
    }

    /**
     * @param string $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        $property = static::convertToCamelCase($property);
        if (\property_exists($this, $property)) {
            return $this->{$property};
        }

        $trace = \debug_backtrace();
        \trigger_error(
            'Undefined property '.$property.
            ' in '.$trace[0]['file'].
            ' on line '.$trace[0]['line'],
            \E_USER_NOTICE
        );

        return null;
    }

    /**
     * @param array $parameters
     *
     * @return void
     */
    public function build(array $parameters): void
    {
        foreach ($parameters as $property => $value) {
            $property = static::convertToCamelCase($property);

            if (\property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $settings = [];
        $called = static::class;

        $reflection = new \ReflectionClass($called);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $prop = $property->getName();
            if (isset($this->$prop) && $property->class == $called) {
                $settings[self::convertToSnakeCase($prop)] = $this->$prop;
            }
        }

        return $settings;
    }

    /**
     * @param string $date DateTime string
     *
     * @return string
     */
    protected static function convertToIso8601(string $date): string
    {
        $date = new \DateTime($date);
        $date->setTimezone(new \DateTimeZone(\date_default_timezone_get()));

        return $date->format(\DateTime::ISO8601);
    }

    /**
     * @param string $str
     *
     * @return string
     */
    protected static function convertToCamelCase(string $str): string
    {
        $callback = function ($match): string {
            return \strtoupper($match[2]);
        };

        $replaced = \preg_replace_callback('/(^|_)([a-z])/', $callback, $str);

        if (null === $replaced) {
            throw new RuntimeException(\sprintf('preg_replace_callback error: %s', \preg_last_error_msg()));
        }

        return \lcfirst($replaced);
    }

    /**
     * @param string $str
     *
     * @return string
     */
    protected static function convertToSnakeCase(string $str): string
    {
        $replaced = \preg_split('/(?=[A-Z])/', $str);

        if (false === $replaced) {
            throw new RuntimeException(\sprintf('preg_split error: %s', \preg_last_error_msg()));
        }

        return \strtolower(\implode('_', $replaced));
    }
}
