<?php

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
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
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
