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
 * @author Michael Shihjay Chen <shihjay2@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
final class AppInstanceSize extends AbstractEntity
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $slug;

    /**
     * @var string
     */
    public $cpuType;

    /**
     * @var string
     */
    public $cpus;

    /**
     * @var string
     */
    public $memoryBytes;

    /**
     * @var string
     */
    public $usdPerMonth;

    /**
     * @var string
     */
    public $usdPerSecond;

    /**
     * @var string
     */
    public $tierSlug;

    /**
     * @var string
     */
    public $tierUpgradeTo;

    /**
     * @var string
     */
    public $tierDowngradeTo;
}
