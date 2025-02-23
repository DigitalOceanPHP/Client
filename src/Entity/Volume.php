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
        parent::build($parameters);

        foreach ($parameters as $property => $value) {
            switch ($property) {
                case 'region':
                    if (\is_object($value)) {
                        $this->region = new Region($value);
                    }
                    unset($parameters[$property]);

                    break;
            }
        }
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = static::convertToIso8601($createdAt);
    }
}
