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
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
final class Size extends AbstractEntity
{
    public string $slug;

    public bool $available;

    public int $memory;

    public int $vcpus;

    public int $disk;

    public int $transfer;

    public int|float $priceMonthly;

    public int|float $priceHourly;

    /**
     * @var string[]
     */
    public array $regions = [];

    public string $description;
}
