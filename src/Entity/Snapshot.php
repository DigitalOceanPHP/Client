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
 */
final class Snapshot extends AbstractEntity
{
    public string $id;

    public string $name;

    public string $createdAt;

    public string $resourceId;

    public string $resourceType;

    public int $minDiskSize;

    public float $sizeGigabytes;

    /**
     * @var string[]
     */
    public array $regions = [];

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = static::convertToIso8601($createdAt);
    }
}
