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
use DigitalOceanV2\Entity\AppDeployment;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class AppDeploymentTest extends TestCase
{
    public function testConstructor(): void
    {
        $values = [
            'id' => 123,
            'spec' => [],
            'services' => [],
            'staticSites' => [],
            'workers' => [],
            'jobs' => [],
            'createdAt' => '2021-02-10T17:05:30Z',
            'updatedAt' => '2021-02-10T17:05:30Z',
            'cause' => 'cause',
            'clonedFrom' => 'clonedFrom',
            'progress' => [],
            'phase' => 'phase',
            'tierSlug' => 'basic',
        ];

        $entity = new AppDeployment($values);

        self::assertInstanceOf(AbstractEntity::class, $entity);
        self::assertInstanceOf(AppDeployment::class, $entity);
        self::assertSame($values['id'], $entity->id);
        self::assertSame($values['spec'], $entity->spec);
        self::assertSame($values['services'], $entity->services);
        self::assertSame($values['staticSites'], $entity->staticSites);
        self::assertSame($values['workers'], $entity->workers);
        self::assertSame($values['jobs'], $entity->jobs);
        self::assertSame($values['createdAt'], $entity->createdAt);
        self::assertSame($values['updatedAt'], $entity->updatedAt);
        self::assertSame($values['cause'], $entity->cause);
        self::assertSame($values['clonedFrom'], $entity->clonedFrom);
        self::assertSame($values['progress'], $entity->progress);
        self::assertSame($values['phase'], $entity->phase);
        self::assertSame($values['tierSlug'], $entity->tierSlug);

        self::assertSame($values['staticSites'], $entity->static_sites);
        self::assertSame($values['createdAt'], $entity->created_at);
        self::assertSame($values['updatedAt'], $entity->updated_at);
        self::assertSame($values['clonedFrom'], $entity->cloned_from);
        self::assertSame($values['tierSlug'], $entity->tier_slug);
    }
}
