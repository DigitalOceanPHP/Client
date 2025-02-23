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
    public string $id;

    public array $spec;

    public array $services;

    public array $staticSites;

    public array $workers;

    public array $jobs;

    public string $phaseLastUpdatedAt;

    public string $createdAt;

    public string $updatedAt;

    public string $cause;

    public string $clonedFrom;

    public array $progress;

    public string $phase;

    public string $tierSlug;
}
