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
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
final class FirewallLocations extends AbstractEntity
{
    /**
     * @var array
     */
    public $addresses;

    /**
     * @var array
     */
    public $dropletIds;

    /**
     * @var array
     */
    public $loadBalancerUids;

    /**
     * @var array
     */
    public $tags;
}
