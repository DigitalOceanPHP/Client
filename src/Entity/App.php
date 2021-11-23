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
final class App extends AbstractEntity
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $ownerUuid;

    /**
     * @var array
     */
    public $spec;

    /**
     * @var string
     */
    public $defaultIngress;

    /**
     * @var string
     */
    public $createdAt;

    /**
     * @var string
     */
    public $updatedAt;

    /**
     * @var array
     */
    public $activeDeployment;

    /**
     * @var array
     */
    public $inProgressDeployment;

    /**
     * @var string
     */
    public $lastDeploymentCreatedAt;

    /**
     * @var string
     */
    public $liveUrl;

    /**
     * @var array
     */
    public $region;

    /**
     * @var string
     */
    public $tierSlug;

    /**
     * @var string
     */
    public $liveUrlBase;

    /**
     * @var string
     */
    public $liveDomain;

    /**
     * @var array
     */
    public $domains;
}
