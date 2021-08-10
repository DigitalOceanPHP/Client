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

namespace DigitalOceanV2\Tests\Entity;

use DigitalOceanV2\Entity\AbstractEntity;
use DigitalOceanV2\Entity\AppInstanceSize;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <graham@alt-three.com>
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

        $this->assertInstanceOf(AbstractEntity::class, $entity);
        $this->assertInstanceOf(AppInstanceSize::class, $entity);
        $this->assertSame($values['name'], $entity->name);
        $this->assertSame($values['slug'], $entity->slug);
        $this->assertSame($values['cpuType'], $entity->cpuType);
        $this->assertSame($values['cpus'], $entity->cpus);
        $this->assertSame($values['memoryBytes'], $entity->memoryBytes);
        $this->assertSame($values['usdPerMonth'], $entity->usdPerMonth);
        $this->assertSame($values['usdPerSecond'], $entity->usdPerSecond);
        $this->assertSame($values['tierSlug'], $entity->tierSlug);
        $this->assertSame($values['tierUpgradeTo'], $entity->tierUpgradeTo);
        $this->assertSame($values['tierDowngradeTo'], $entity->tierDowngradeTo);

        $this->assertSame($values['cpuType'], $entity->cpu_type);
        $this->assertSame($values['memoryBytes'], $entity->memory_bytes);
        $this->assertSame($values['usdPerMonth'], $entity->usd_per_month);
        $this->assertSame($values['usdPerSecond'], $entity->usd_per_second);
        $this->assertSame($values['tierSlug'], $entity->tier_slug);
        $this->assertSame($values['tierUpgradeTo'], $entity->tier_upgrade_to);
        $this->assertSame($values['tierDowngradeTo'], $entity->tier_downgrade_to);
    }
}