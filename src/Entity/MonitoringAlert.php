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
final class MonitoringAlert extends AbstractEntity
{
    /**
     * @var array
     */
    public $alerts;

    /**
     * @var string
     */
    public $compare;

    /**
     * @var string
     */
    public $description;

    /**
     * @var bool
     */
    public $enabled;

    /**
     * @var array
     */
    public $entities;

    /**
     * @var array
     */
    public $tags;

    /**
     * @var string
     */
    public $type;

    /**
     * @var int
     */
    public $value;

    /**
     * @var string
     */
    public $window;
}
