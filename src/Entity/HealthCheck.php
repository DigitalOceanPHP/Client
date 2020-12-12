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
 * @author Jacob Holmes <jwh315@cox.net>
 */
class HealthCheck extends AbstractEntity
{
    /**
     * @var string
     */
    public $protocol;

    /**
     * @var int
     */
    public $port;

    /**
     * @var string
     */
    public $path;

    /**
     * @var int
     */
    public $checkIntervalSeconds;

    /**
     * @var int
     */
    public $responseTimeoutSeconds;

    /**
     * @var int
     */
    public $healthyThreshold;

    /**
     * @var int
     */
    public $unhealthyThreshold;
}
