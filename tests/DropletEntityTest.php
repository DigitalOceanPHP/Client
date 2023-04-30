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
use DigitalOceanV2\Entity\Image;
use DigitalOceanV2\Entity\Region;
use DigitalOceanV2\Entity\Size;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class DropletEntityTest extends TestCase
{
    public function testConstructor(): void
    {
        $values = [
            'id' => 3164444,
            'name' => 'example.com',
            'memory' => 1024,
            'vcpus' => 1,
            'disk' => 25,
            'locked' => false,
            'status' => 'active',
            'kernel' => null,
            'created_at' => '2020-07-21T18:37:44Z',
            'features' => [
                'backups',
                'private_networking',
                'ipv6',
            ],
            'backup_ids' => [
                53893572,
            ],
            'next_backup_window' => [
                'start' => '2020-07-30T00:00:00Z',
                'end' => '2020-07-30T23:00:00Z',
            ],
            'snapshot_ids' => [
                67512819,
            ],
            'image' => (object) [
                'id' => 63663980,
                'name' => '20.04 (LTS) x64',
                'distribution' => 'Ubuntu',
                'slug' => 'ubuntu-20-04-x64',
                'public' => true,
                'regions' => [
                    'ams2',
                    'ams3',
                    'blr1',
                    'fra1',
                    'tor1',
                ],
                'created_at' => '2020-05-15T05:47:50Z',
                'type' => 'snapshot',
                'min_disk_size' => 20,
                'size_gigabytes' => 2.36,
                'description' => '',
                'tags' => [],
                'status' => 'available',
                'error_message' => '',
            ],
            'volume_ids' => [],
            'size' => (object) [
                'slug' => 's-1vcpu-1gb',
                'memory' => 1024,
                'vcpus' => 1,
                'disk' => 25,
                'transfer' => 1,
                'price_monthly' => 5,
                'price_hourly' => 0.00743999984115362,
                'regions' => [
                    'ams2',
                    'ams3',
                    'blr1',
                    'sgp1',
                    'tor1',
                ],
                'available' => true,
                'description' => 'Basic',
            ],
            'size_slug' => 's-1vcpu-1gb',
            'region' => (object) [
                'name' => 'New York 3',
                'slug' => 'nyc3',
                'features' => [
                    'private_networking',
                    'backups',
                    'ipv6',
                ],
                'available' => true,
                'sizes' => [
                    's-1vcpu-1gb',
                    's-1vcpu-2gb',
                    's-1vcpu-3gb',
                ],
            ],
            'tags' => [
                'web',
                'env:prod',
            ],
            'vpc_uuid' => '760e09ef-dc84-11e8-981e-3cfdfeaae000',
        ];
        $droplet = new Droplet($values);

        self::assertInstanceOf(AbstractEntity::class, $droplet);
        self::assertInstanceOf(Droplet::class, $droplet);
        self::assertInstanceOf(Region::class, $droplet->region);
        self::assertInstanceOf(Size::class, $droplet->size);
        self::assertInstanceOf(Image::class, $droplet->image);
        self::assertSame($values['id'], $droplet->id);
        self::assertSame($values['name'], $droplet->name);
        self::assertSame($values['memory'], $droplet->memory);
        self::assertSame($values['vcpus'], $droplet->vcpus);
        self::assertSame($values['disk'], $droplet->disk);
        self::assertSame($values['size_slug'], $droplet->sizeSlug);
        self::assertSame($values['locked'], $droplet->locked);
        self::assertSame($values['status'], $droplet->status);
        self::assertSame($values['created_at'], $droplet->createdAt);
        self::assertSame($values['features'], $droplet->features);
        self::assertSame($values['tags'], $droplet->tags);
        self::assertSame($values['vpc_uuid'], $droplet->vpcUuid);
    }
}
