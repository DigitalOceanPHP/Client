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

        self::assertInstanceOf(AbstractEntity::class, $entity);
        self::assertInstanceOf(AppTier::class, $entity);
        self::assertSame($values['name'], $entity->name);
        self::assertSame($values['slug'], $entity->slug);
        self::assertSame($values['storageBytes'], $entity->storageBytes);
        self::assertSame($values['egressBandwidthBytes'], $entity->egressBandwidthBytes);
        self::assertSame($values['buildSeconds'], $entity->buildSeconds);

        self::assertSame($values['storageBytes'], $entity->storage_bytes);
        self::assertSame($values['egressBandwidthBytes'], $entity->egress_bandwidth_bytes);
        self::assertSame($values['buildSeconds'], $entity->build_seconds);
    }
}
