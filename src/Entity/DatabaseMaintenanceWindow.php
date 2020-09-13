<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Entity;

/**
 * @author Filippo Fortino <filippofortino@gmail.com>
 */
final class DatabaseMaintenanceWindow extends AbstractEntity
{
    /**
     * @var string
     */
    public $day;

    /**
     * @var string
     */
    public $hour;

    /**
     * @var bool
     */
    public $pending;

    /**
     * @var string[]
     */
    public $description = [];
}
