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
 * @author Michael Shihjay Chen <shihjay2@gmail.com>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
final class AppInstanceSize extends AbstractEntity
{
    public string $name;

    public string $slug;

    public string $cpuType;

    public string $cpus;

    public string $memoryBytes;

    public string $usdPerMonth;

    public string $usdPerSecond;

    public string $tierSlug;

    public string $tierUpgradeTo;

    public string $tierDowngradeTo;
}
