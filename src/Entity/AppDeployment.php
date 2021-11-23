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
    public $staticSites;

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
    public $phaseLastUpdatedAt;

    /**
     * @var string
     */
    public $createdAt;

    /**
     * @var string
     */
    public $updatedAt;

    /**
     * @var string
     */
    public $cause;

    /**
     * @var string
     */
    public $clonedFrom;

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
    public $tierSlug;
}
