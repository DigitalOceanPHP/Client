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
final class Network extends AbstractEntity
{
    /**
     * @var string
     */
    public $ipAddress;

    /**
     * @var string
     */
    public $gateway;

    /**
     * @var string
     */
    public $type;

    /**
     * IPv4 or IPv6.
     *
     * @var int
     */
    public $version;

    /**
     * IPv6 specific.
     *
     * @var string|null
     */
    public $cidr;

    /**
     * IPv4 specific.
     *
     * @var string|null
     */
    public $netmask;
}
