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
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $origin;

    /**
     * @var string
     */
    public $endpoint;

    /**
     * @var string
     */
    public $createdAt;

    /**
     * @var int
     */
    public $ttl;

    /**
     * @var string
     */
    public $certificateId;

    /**
     * @var string
     */
    public $customDomain;

    /**
     * @param string $createdAt
     *
     * @return void
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = static::convertToIso8601($createdAt);
    }
}
