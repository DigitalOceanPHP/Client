<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOcean API library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Entity;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 * @author Graham Campbell <graham@alt-three.com>
 */
final class Action extends AbstractEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string|null
     */
    public $startedAt;

    /**
     * @var string|null
     */
    public $completedAt;

    /**
     * @var string
     */
    public $resourceId;

    /**
     * @var string
     */
    public $resourceType;

    /**
     * @var Region
     */
    public $region;

    /**
     * @var string
     */
    public $regionSlug;

    /**
     * @param array $parameters
     *
     * @return void
     */
    public function build(array $parameters): void
    {
        parent::build($parameters);

        foreach ($parameters as $property => $value) {
            if ('region' === $property && \is_object($value)) {
                $this->region = new Region($value);
            }
        }
    }

    /**
     * @param string $startedAt
     *
     * @return void
     */
    public function setStartedAt(string $startedAt): void
    {
        $this->startedAt = static::convertToIso8601($startedAt);
    }

    /**
     * @param string|null $completedAt
     *
     * @return void
     */
    public function setCompletedAt(?string $completedAt): void
    {
        $this->completedAt = null === $completedAt ? null : static::convertToIso8601($completedAt);
    }
}
