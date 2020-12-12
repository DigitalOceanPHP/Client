<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOcean API library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Tests;

use DigitalOceanV2\Entity\AbstractEntity;
use DigitalOceanV2\Entity\Droplet;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <graham@alt-three.com>
 */
class DropletEntityTest extends TestCase
{
    public function testConstructor(): void
    {
        $droplet = new Droplet([
            'id' => 123,
            'name' => 'Dave',
        ]);

        $this->assertInstanceOf(AbstractEntity::class, $droplet);
        $this->assertInstanceOf(Droplet::class, $droplet);
        $this->assertSame(123, $droplet->id);
        $this->assertSame('Dave', $droplet->name);
    }
}
