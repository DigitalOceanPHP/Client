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
final class AppDeployment extends AbstractEntity
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var array
     */
    public $spec;

    /**
     * @var array
     */
    public $services;

    /**
     * @var array
     */
    public $static_sites;

    /**
     * @var array
     */
    public $workers;

    /**
     * @var array
     */
    public $jobs;

    /**
     * @var string
     */
    public $phase_last_updated_at;

    /**
     * @var string
     */
    public $created_at;

    /**
     * @var string
     */
    public $updated_at;

    /**
     * @var string
     */
    public $cause;

    /**
     * @var string
     */
    public $cloned_from;

    /**
     * @var array
     */
    public $progress;

    /**
     * @var string
     */
    public $phase;

    /**
     * @var string
     */
    public $tier_slug;
}
