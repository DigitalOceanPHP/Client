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
    public int $id;

    public string $name;

    public string $type;

    public string $distribution;

    public string $slug;

    public int $minDiskSize;

    public float $sizeGigabytes;

    public string $createdAt;

    public bool $public;

    /**
     * @var string[]
     */
    public array $regions = [];

    public string $description;

    /**
     * @var string[]
     */
    public array $tags = [];

    public string $status;

    public string $error_message;

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = static::convertToIso8601($createdAt);
    }
}
