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
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 */
class Image extends AbstractEntity
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $distribution;

    /**
     * @var string
     */
    public $slug;

    /**
     * @var integer
     */
    public $minDiskSize;

    /**
     * @var string
     */
    public $createdAt;

    /**
     * @var boolean
     */
    public $public;

    /**
     * @var string[]
     */
    public $regions;

    /**
     * @var integer[]
     */
    public $actionIds;

    /**
     * @param string $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $this->convertDateTime($createdAt);
    }
}
