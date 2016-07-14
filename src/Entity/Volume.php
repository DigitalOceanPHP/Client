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
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 */
final class Volume extends AbstractEntity
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var Region
     */
    public $region;

    /**
     * @var int[]
     */
    public $dropletIds = [];

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $sizeGigabytes;

    /**
     * @var string
     */
    public $createdAt;

    /**
     * @param array $parameters
     */
    public function build(array $parameters)
    {
        parent::build($parameters);

        foreach ($parameters as $property => $value) {
            switch ($property) {
                case 'region':
                    if (is_object($value)) {
                        $this->region = new Region($value);
                    }
                    unset($parameters[$property]);
                    break;
            }
        }
    }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = static::convertDateTime($createdAt);
    }
}
