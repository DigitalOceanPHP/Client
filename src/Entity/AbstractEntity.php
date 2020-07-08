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

namespace DigitalOceanV2\Entity;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 * @author Graham Campbell <graham@alt-three.com>
 */
abstract class AbstractEntity
{
    /**
     * @param \stdClass|array|null $parameters
     *
     * @return void
     */
    public function __construct($parameters = null)
    {
        if (!$parameters) {
            return;
        }

        if ($parameters instanceof \stdClass) {
            $parameters = get_object_vars($parameters);
        }

        $this->build($parameters);
    }

    /**
     * @param array $parameters
     *
     * @return void
     */
    public function build(array $parameters)
    {
        foreach ($parameters as $property => $value) {
            $property = static::convertToCamelCase($property);

            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $settings = [];
        $called = get_called_class();

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
     * @return string DateTime in ISO8601 format
     */
    protected static function convertToIso8601(string $date)
    {
        $date = new \DateTime($date);
        $date->setTimezone(new \DateTimeZone(date_default_timezone_get()));

        return $date->format(\DateTime::ISO8601);
    }

    /**
     * @param string $str
     *
     * @return string
     */
    protected static function convertToCamelCase(string $str)
    {
        $callback = function ($match) {
            return strtoupper($match[2]);
        };

        return lcfirst(preg_replace_callback('/(^|_)([a-z])/', $callback, $str));
    }

    /**
     * @param string $str
     *
     * @return string
     */
    protected static function convertToSnakeCase(string $str)
    {
        return strtolower(implode('_', preg_split('/(?=[A-Z])/', $str)));
    }
}
