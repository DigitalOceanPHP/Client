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
use DigitalOceanV2\Entity\AppInstanceSize;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class AppInstanceSizeTest extends TestCase
{
    public function testConstructor(): void
    {
        $values = [
            'name' => 'name',
            'slug' => 'slug',
            'cpuType' => 'cpuType',
            'cpus' => 'cpus',
            'memoryBytes' => 'memoryBytes',
            'usdPerMonth' => 'usdPerMonth',
            'usdPerSecond' => 'usdPerSecond',
            'tierSlug' => 'tierSlug',
            'tierUpgradeTo' => 'tierUpgradeTo',
            'tierDowngradeTo' => 'tierDowngradeTo',
        ];

        $entity = new AppInstanceSize($values);

        self::assertInstanceOf(AbstractEntity::class, $entity);
        self::assertInstanceOf(AppInstanceSize::class, $entity);
        self::assertSame($values['name'], $entity->name);
        self::assertSame($values['slug'], $entity->slug);
        self::assertSame($values['cpuType'], $entity->cpuType);
        self::assertSame($values['cpus'], $entity->cpus);
        self::assertSame($values['memoryBytes'], $entity->memoryBytes);
        self::assertSame($values['usdPerMonth'], $entity->usdPerMonth);
        self::assertSame($values['usdPerSecond'], $entity->usdPerSecond);
        self::assertSame($values['tierSlug'], $entity->tierSlug);
        self::assertSame($values['tierUpgradeTo'], $entity->tierUpgradeTo);
        self::assertSame($values['tierDowngradeTo'], $entity->tierDowngradeTo);

        self::assertSame($values['cpuType'], $entity->cpu_type);
        self::assertSame($values['memoryBytes'], $entity->memory_bytes);
        self::assertSame($values['usdPerMonth'], $entity->usd_per_month);
        self::assertSame($values['usdPerSecond'], $entity->usd_per_second);
        self::assertSame($values['tierSlug'], $entity->tier_slug);
        self::assertSame($values['tierUpgradeTo'], $entity->tier_upgrade_to);
        self::assertSame($values['tierDowngradeTo'], $entity->tier_downgrade_to);
    }
}
