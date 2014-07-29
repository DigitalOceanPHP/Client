<?php

/**
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
     * @param \stdClass|array $parameters
     */
    public function __construct($parameters)
    {
        $this->build($parameters);
    }

    /**
     * @param  string                    $property
     * @throws \InvalidArgumentException
     * @return mixed
     */
    public function __get($property)
    {
        if (!property_exists($this, $property)) {
            throw new \InvalidArgumentException(sprintf(
                'Property "%s::%s" does not exist.', get_class($this), $property)
            );
        }
    }

    /**
     * @param  string                    $property
     * @param  mixed                     $value
     * @throws \InvalidArgumentException
     */
    public function __set($property, $value)
    {
        if (!property_exists($this, $property)) {
            throw new \InvalidArgumentException(sprintf(
                'Property "%s::%s" should exist.', get_class($this), $property)
            );
        }
    }

    /**
     * @param \stdClass|array $parameters
     */
    public function build($parameters)
    {
        foreach ($parameters as $property => $value) {
            $property = \DigitalOceanV2\convert_to_camel_case($property);

            if (method_exists(get_called_class(), 'set' . $property)) {
                // To complatible with php =< 5.4
                $property = 'set' . $property;
                $this->$property($value);
            } else {
                $this->$property = $value;
            }
        }
    }

    /**
     * @param  string      $date DateTime string
     * @return null|string       DateTime in ISO8601 format
     */
    protected function convertDateTime($date)
    {
        if (empty($date)) {
            return null;
        }

        $date = new \DateTime($date);
        $date->setTimezone(new \DateTimeZone(date_default_timezone_get()));

        return $date->format(\DateTime::ISO8601);
    }
}
