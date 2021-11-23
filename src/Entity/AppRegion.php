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
final class AppRegion extends AbstractEntity
{
    /**
     * @var string
     */
    public $slug;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $flag;

    /**
     * @var string
     */
    public $continent;

    /**
     * @var bool
     */
    public $disabled;

    /**
     * @var array
     */
    public $dataCenters;

    /**
     * @var string
     */
    public $reason;

    /**
     * @var bool
     */
    public $default;
}
