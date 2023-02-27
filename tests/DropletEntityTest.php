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

namespace DigitalOceanV2\Tests;

use DigitalOceanV2\Entity\AbstractEntity;
use DigitalOceanV2\Entity\Droplet;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class DropletEntityTest extends TestCase
{
    public function testConstructor(): void
    {
        $droplet = new Droplet([
            'id' => 123,
            'name' => 'Dave',
        ]);

        self::assertInstanceOf(AbstractEntity::class, $droplet);
        self::assertInstanceOf(Droplet::class, $droplet);
        self::assertSame(123, $droplet->id);
        self::assertSame('Dave', $droplet->name);
    }
}
