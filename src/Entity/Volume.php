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
final class Volume extends AbstractEntity
{
    public string $id;

    public Region $region;

    /**
     * @var int[]
     */
    public array $dropletIds = [];

    public string $name;

    public string $description;

    public int $sizeGigabytes;

    public string $createdAt;

    public string $filesystemType;

    public string $filesystemLabel;

    /**
     * @var Tag[]
     */
    public array $tags = [];

    public function build(array $parameters): void
    {
        foreach ($parameters as $property => $value) {
            $property = static::convertToCamelCase($property);

            if ('region' === $property) {
                $this->region = new Region($value);
            } elseif ('dropletIds' === $property && null === $value) {
                $this->dropletIds = [];
            } elseif (\property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = static::convertToIso8601($createdAt);
    }
}
