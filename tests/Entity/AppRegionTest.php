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
use DigitalOceanV2\Entity\AppRegion;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <graham@alt-three.com>
 */
class AppRegionTest extends TestCase
{
    public function testConstructor(): void
    {
        $values = [
            'slug' => 'slug',
            'label' => 'label',
            'flag' => 'flag',
            'continent' => 'continent',
            'disabled' => false,
            'dataCenters' => [],
            'reason' => 'reason',
            'default' => true,
        ];

        $entity = new AppRegion($values);

        $this->assertInstanceOf(AbstractEntity::class, $entity);
        $this->assertInstanceOf(AppRegion::class, $entity);
        $this->assertSame($values['slug'], $entity->slug);
        $this->assertSame($values['label'], $entity->label);
        $this->assertSame($values['flag'], $entity->flag);
        $this->assertSame($values['continent'], $entity->continent);
        $this->assertSame($values['dataCenters'], $entity->dataCenters);
        $this->assertSame($values['reason'], $entity->reason);
        $this->assertSame($values['default'], $entity->default);

        $this->assertSame($values['dataCenters'], $entity->data_centers);
    }
}
