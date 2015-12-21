<?php

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
 * @author Antoine Corcy <contact@sbin.dk>
 * @author Graham Campbell <graham@alt-three.com>
 */
final class Upgrade extends AbstractEntity
{
    /**
     * @var int
     */
    public $dropletId;

    /**
     * @var string
     */
    public $dateOfMigration;

    /**
     * @var string
     */
    public $url;

    /**
     * @param string $dateOfMigration
     */
    public function setDateOfMigration($dateOfMigration)
    {
        $this->dateOfMigration = static::convertDateTime($dateOfMigration);
    }
}
