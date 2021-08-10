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
use DigitalOceanV2\Entity\AppDeployment;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <graham@alt-three.com>
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
            'tierSlug' => 'basic'
        ];

        $entity = new AppDeployment($values);

        $this->assertInstanceOf(AbstractEntity::class, $entity);
        $this->assertInstanceOf(AppDeployment::class, $entity);
        $this->assertSame($values['id'], $entity->id);
        $this->assertSame($values['spec'], $entity->spec);
        $this->assertSame($values['services'], $entity->services);
        $this->assertSame($values['staticSites'], $entity->staticSites);
        $this->assertSame($values['workers'], $entity->workers);
        $this->assertSame($values['jobs'], $entity->jobs);
        $this->assertSame($values['createdAt'], $entity->createdAt);
        $this->assertSame($values['updatedAt'], $entity->updatedAt);
        $this->assertSame($values['cause'], $entity->cause);
        $this->assertSame($values['clonedFrom'], $entity->clonedFrom);
        $this->assertSame($values['progress'], $entity->progress);
        $this->assertSame($values['phase'], $entity->phase);
        $this->assertSame($values['tierSlug'], $entity->tierSlug);


        $this->assertSame($values['staticSites'], $entity->static_sites);
        $this->assertSame($values['createdAt'], $entity->created_at);
        $this->assertSame($values['updatedAt'], $entity->updated_at);
        $this->assertSame($values['clonedFrom'], $entity->cloned_from);
        $this->assertSame($values['tierSlug'], $entity->tier_slug);
    }
}