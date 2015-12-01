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
 * @author Cagatay Gürtürk <info@cagataygurturk.com>
 */
class FloatingIp extends AbstractEntity
{
    /**
     * @var int
     */
    public $ip;

    /**
     * @var Droplet
     */
    public $droplet;

    /**
     * @var Region
     */
    public $region;

    /**
     * @var array
     */
    public $features;

    /**
     * @var bool
     */
    public $locked;


    /**
     * @param \stdClass|array $parameters
     */
    public function build($parameters)
    {
        foreach ($parameters as $property => $value) {
            switch ($property) {
                case 'region':
                    if (is_object($value)) {
                        $this->region = new Region($value);
                    }
                    break;
                case 'droplet':
                    if (is_object($value)) {
                        $this->droplet = new Droplet($value);
                    }
                    break;
                default:
                    $this->{\DigitalOceanV2\convert_to_camel_case($property)} = $value;
            }
        }
    }
}
