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
 * @author Roy de Jong <roy@softwarepunt.nl>
 */
final class Team extends AbstractEntity
{
    /**
     * @var string
     */
    public $uuid;

    /**
     * @var string
     */
    public $name;
}
