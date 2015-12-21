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
 * @author Antoine Corcy <contact@sbin.dk>
 * @author Graham Campbell <graham@alt-three.com>
 */
final class Account extends AbstractEntity
{
    /**
     * @var int
     */
    public $dropletLimit;

    /**
     * @var int
     */
    public $floatingIpLimit;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $uuid;

    /**
     * @var bool
     */
    public $emailVerified;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $statusMessage;
}
