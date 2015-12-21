<?php

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
 */
abstract class AbstractEntity
{
    /**
     * @param \stdClass|array|null $parameters
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
     */
    public function build(array $parameters)
    {
        foreach ($parameters as $property => $value) {
            $property = lcfirst(preg_replace_callback('/(^|_)([a-z])/', function ($match) {
                return strtoupper($match[2]);
            }, $property));

            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    /**
     * @param string $date DateTime string
     *
     * @return string|null DateTime in ISO8601 format
     */
    protected function convertDateTime($date)
    {
        if (empty($date)) {
            return;
        }

        $date = new \DateTime($date);
        $date->setTimezone(new \DateTimeZone(date_default_timezone_get()));

        return $date->format(\DateTime::ISO8601);
    }
}
