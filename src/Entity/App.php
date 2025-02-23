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
    public string $id;

    public string $ownerUuid;

    public array $spec;

    public string $defaultIngress;

    public string $createdAt;

    public string $updatedAt;

    public array $activeDeployment;

    public array $inProgressDeployment;

    public string $lastDeploymentCreatedAt;

    public string $liveUrl;

    public array $region;

    public string $tierSlug;

    public string $liveUrlBase;

    public string $liveDomain;

    public array $domains;
}
