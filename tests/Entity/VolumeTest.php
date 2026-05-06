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
use DigitalOceanV2\Entity\Region;
use DigitalOceanV2\Entity\Volume;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class VolumeTest extends TestCase
{
    public function testConstructor(): void
    {
        $values = [
            'id' => '506f78a4-e098-11e5-ad9f-000f53306ae1',
            'region' => [
                'name' => 'New York 1',
                'slug' => 'nyc1',
                'available' => true,
                'features' => [
                    'private_networking',
                ],
                'sizes' => [
                    's-1vcpu-1gb',
                ],
            ],
            'droplet_ids' => [3164444],
            'name' => 'example',
            'description' => 'Block store for examples',
            'size_gigabytes' => 10,
            'created_at' => '2020-03-02T17:00:49Z',
            'filesystem_type' => 'ext4',
            'filesystem_label' => 'example',
            'tags' => [
                'aninterestingtag',
            ],
        ];

        $entity = new Volume($values);

        self::assertInstanceOf(AbstractEntity::class, $entity);
        self::assertInstanceOf(Volume::class, $entity);
        self::assertInstanceOf(Region::class, $entity->region);
        self::assertSame($values['id'], $entity->id);
        self::assertSame($values['droplet_ids'], $entity->dropletIds);
        self::assertSame($values['name'], $entity->name);
        self::assertSame($values['description'], $entity->description);
        self::assertSame($values['size_gigabytes'], $entity->sizeGigabytes);
        self::assertSame($values['created_at'], $entity->createdAt);
        self::assertSame($values['filesystem_type'], $entity->filesystemType);
        self::assertSame($values['filesystem_label'], $entity->filesystemLabel);
        self::assertSame($values['tags'], $entity->tags);
    }

    public function testConstructorAcceptsNullDropletIds(): void
    {
        $entity = new Volume([
            'id' => '506f78a4-e098-11e5-ad9f-000f53306ae1',
            'region' => [
                'name' => 'New York 1',
                'slug' => 'nyc1',
                'available' => true,
                'features' => [],
                'sizes' => [],
            ],
            'droplet_ids' => null,
            'name' => 'example',
            'description' => 'Block store for examples',
            'size_gigabytes' => 10,
            'created_at' => '2020-03-02T17:00:49Z',
            'filesystem_type' => 'ext4',
            'filesystem_label' => 'example',
        ]);

        self::assertSame([], $entity->dropletIds);
    }
}
