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
use DigitalOceanV2\Entity\App;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class AppTest extends TestCase
{
    public function testConstructor(): void
    {
        $values = [
            'id' => 123,
            'ownerUuid' => 'uuid',
            'spec' => [],
            'defaultIngress' => 'defaultIngress',
            'createdAt' => 'createdAt',
            'updatedAt' => 'updatedAt',
            'activeDeployment' => [],
            'inProgressDeployment' => [],
            'lastDeploymentCreatedAt' => 'lastDeploymentCreatedAt',
            'liveUrl' => 'liveUrl',
            'region' => [],
            'tierSlug' => 'tierSlug',
            'liveUrlBase' => 'liveUrlBase',
            'liveDomain' => 'liveDomain',
            'domains' => [],
        ];

        $entity = new App($values);

        $this->assertInstanceOf(AbstractEntity::class, $entity);
        $this->assertInstanceOf(App::class, $entity);
        $this->assertSame($values['id'], $entity->id);
        $this->assertSame($values['ownerUuid'], $entity->ownerUuid);
        $this->assertSame($values['spec'], $entity->spec);
        $this->assertSame($values['defaultIngress'], $entity->defaultIngress);
        $this->assertSame($values['createdAt'], $entity->createdAt);
        $this->assertSame($values['updatedAt'], $entity->updatedAt);
        $this->assertSame($values['activeDeployment'], $entity->activeDeployment);
        $this->assertSame($values['inProgressDeployment'], $entity->inProgressDeployment);
        $this->assertSame($values['lastDeploymentCreatedAt'], $entity->lastDeploymentCreatedAt);
        $this->assertSame($values['liveUrl'], $entity->liveUrl);
        $this->assertSame($values['region'], $entity->region);
        $this->assertSame($values['tierSlug'], $entity->tierSlug);
        $this->assertSame($values['liveUrlBase'], $entity->liveUrlBase);
        $this->assertSame($values['liveDomain'], $entity->liveDomain);
        $this->assertSame($values['domains'], $entity->domains);

        $this->assertSame($values['ownerUuid'], $entity->owner_uuid);
        $this->assertSame($values['defaultIngress'], $entity->default_ingress);
        $this->assertSame($values['createdAt'], $entity->created_at);
        $this->assertSame($values['updatedAt'], $entity->updated_at);
        $this->assertSame($values['activeDeployment'], $entity->active_deployment);
        $this->assertSame($values['inProgressDeployment'], $entity->in_progress_deployment);
        $this->assertSame($values['lastDeploymentCreatedAt'], $entity->last_deployment_created_at);
        $this->assertSame($values['liveUrl'], $entity->live_url);
        $this->assertSame($values['tierSlug'], $entity->tier_slug);
        $this->assertSame($values['liveUrlBase'], $entity->live_url_base);
        $this->assertSame($values['liveDomain'], $entity->live_domain);
    }
}
