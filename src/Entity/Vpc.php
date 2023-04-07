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
 * @author Manuel Christlieb <manuel@gchristlieb.eu>
 */
final class Vpc extends AbstractEntity
{
    public string $name;

    public string $description;

    public string $region;

    public string $ipRange;

    public string $id;

    public string $urn;

    public bool $default;

    public string $createdAt;
}
