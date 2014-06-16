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

        return $this->$property;
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

        $this->$property = $value;
    }

    /**
     * @param \stdClass|array $parameters
     */
    public function build($parameters)
    {
        foreach ($parameters as $property => $value) {
            $this->{\DigitalOceanV2\convert_to_camel_case($property)} = $value;
        }
    }
}
