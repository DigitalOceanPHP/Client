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

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
final class Image extends AbstractEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $distribution;

    /**
     * @var string
     */
    public $slug;

    /**
     * @var int
     */
    public $minDiskSize;

    /**
     * @var float
     */
    public $sizeGigabytes;

    /**
     * @var string
     */
    public $createdAt;

    /**
     * @var bool
     */
    public $public;

    /**
     * @var string[]
     */
    public $regions = [];

    /**
     * @var string
     */
    public $description;

    /**
     * @var string[]
     */
    public $tags = [];

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $error_message;

    /**
     * @param string $createdAt
     *
     * @return void
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = static::convertToIso8601($createdAt);
    }
}
