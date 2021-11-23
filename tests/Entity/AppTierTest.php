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

namespace DigitalOceanV2\Tests\Entity;

use DigitalOceanV2\Entity\AbstractEntity;
use DigitalOceanV2\Entity\AppTier;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class AppTierTest extends TestCase
{
    public function testConstructor(): void
    {
        $values = [
            'name' => 'name',
            'slug' => 'slug',
            'storageBytes' => 'storageBytes',
            'egressBandwidthBytes' => 'egressBandwidthBytes',
            'buildSeconds' => 'buildSeconds',
        ];

        $entity = new AppTier($values);

        $this->assertInstanceOf(AbstractEntity::class, $entity);
        $this->assertInstanceOf(AppTier::class, $entity);
        $this->assertSame($values['name'], $entity->name);
        $this->assertSame($values['slug'], $entity->slug);
        $this->assertSame($values['storageBytes'], $entity->storageBytes);
        $this->assertSame($values['egressBandwidthBytes'], $entity->egressBandwidthBytes);
        $this->assertSame($values['buildSeconds'], $entity->buildSeconds);

        $this->assertSame($values['storageBytes'], $entity->storage_bytes);
        $this->assertSame($values['egressBandwidthBytes'], $entity->egress_bandwidth_bytes);
        $this->assertSame($values['buildSeconds'], $entity->build_seconds);
    }
}
