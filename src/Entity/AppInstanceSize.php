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
    public $cpu_type;

    /**
     * @var string
     */
    public $cpus;

    /**
     * @var string
     */
    public $memory_bytes;

    /**
     * @var string
     */
    public $usd_per_month;

    /**
     * @var string
     */
    public $usd_per_second;

    /**
     * @var string
     */
    public $tier_slug;

    /**
     * @var string
     */
    public $tier_upgrade_to;

    /**
     * @var string
     */
    public $tier_downgrade_to;
}
