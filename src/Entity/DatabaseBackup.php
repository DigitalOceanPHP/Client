<?php

declare(strict_types=1);

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
 * @author Filippo Fortino <filippofortino@gmail.com>
 */
final class DatabaseBackup extends AbstractEntity
{
    /**
     * @var string
     */
    public $createdAt;

    /**
     * @var float
     */
    public $sizeGigabytes;

    /**
     * @param string $createdAt
     *
     * @return void
     */
    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = static::convertToIso8601($createdAt);
    }
}
