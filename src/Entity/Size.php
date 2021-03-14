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
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
final class Size extends AbstractEntity
{
    /**
     * @var string
     */
    public $slug;

    /**
     * @var bool
     */
    public $available;

    /**
     * @var int
     */
    public $memory;

    /**
     * @var int
     */
    public $vcpus;

    /**
     * @var int
     */
    public $disk;

    /**
     * @var int
     */
    public $transfer;

    /**
     * @var string
     */
    public $priceMonthly;

    /**
     * @var string
     */
    public $priceHourly;

    /**
     * @var string[]
     */
    public $regions = [];

    /**
     * @var string
     */
    public $description;
}
