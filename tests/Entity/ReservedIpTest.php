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
use DigitalOceanV2\Entity\Region as RegionEntity;
use DigitalOceanV2\Entity\ReservedIp as ReservedIpEntity;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 * @author Manuel Christlieb <manuel@christlieb.eu>
 */
class ReservedIpTest extends TestCase
{
    public function testConstructor(): void
    {
        $values = [
            'ip' => '45.55.96.47',
            'droplet' => null,
            'region' => [
                'name' => 'New York 3',
                'slug' => 'nyc3',
                'features' => [
                    'private_networking',
                    'backups',
                    'image_transfer',
                ],
                'available' => true,
                'sizes' => [
                    's-1vcpu-1gb',
                    's-1vcpu-2gb',
                    's-32vcpu-192g',
                ],
            ],
            'locked' => false,
            'project_id' => '746c6152-2fa2-11ed-92d3-27aaa54e4988',
        ];

        $entity = new ReservedIpEntity($values);

        self::assertInstanceOf(AbstractEntity::class, $entity);
        self::assertInstanceOf(ReservedIpEntity::class, $entity);
        self::assertInstanceOf(RegionEntity::class, $entity->region);
        self::assertSame($values['ip'], $entity->ip);
        self::assertNull($entity->droplet);

        self::assertSame($values['locked'], $entity->locked);
        self::assertSame($values['project_id'], $entity->projectId);
    }
}
