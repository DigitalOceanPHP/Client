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
 * @author Antoine Kirk <contact@sbin.dk>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
final class Action extends AbstractEntity
{
    public int $id;

    public string $status;

    public string $type;

    public ?string $startedAt;

    public ?string $completedAt;

    public string $resourceId;

    public string $resourceType;

    public Region $region;

    public string $regionSlug;

    public function build(array $parameters): void
    {
        parent::build($parameters);

        foreach ($parameters as $property => $value) {
            if ('region' === $property && \is_object($value)) {
                $this->region = new Region($value);
            }
        }
    }

    public function setStartedAt(string $startedAt): void
    {
        $this->startedAt = static::convertToIso8601($startedAt);
    }

    public function setCompletedAt(?string $completedAt): void
    {
        $this->completedAt = null === $completedAt ? null : static::convertToIso8601($completedAt);
    }
}
