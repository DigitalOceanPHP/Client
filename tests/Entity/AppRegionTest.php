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
use DigitalOceanV2\Entity\AppRegion;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
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

        self::assertInstanceOf(AbstractEntity::class, $entity);
        self::assertInstanceOf(AppRegion::class, $entity);
        self::assertSame($values['slug'], $entity->slug);
        self::assertSame($values['label'], $entity->label);
        self::assertSame($values['flag'], $entity->flag);
        self::assertSame($values['continent'], $entity->continent);
        self::assertSame($values['dataCenters'], $entity->dataCenters);
        self::assertSame($values['reason'], $entity->reason);
        self::assertSame($values['default'], $entity->default);

        self::assertSame($values['dataCenters'], $entity->data_centers);
    }
}
