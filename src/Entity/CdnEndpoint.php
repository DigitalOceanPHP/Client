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
 * @author Christian Fuentes <christian@topworksheets.com>
 */
final class CdnEndpoint extends AbstractEntity
{
    public string $id;

    public string $origin;

    public string $endpoint;

    public string $createdAt;

    public int $ttl;

    public string $certificateId;

    public string $customDomain;

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = static::convertToIso8601($createdAt);
    }
}
