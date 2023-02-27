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

        self::assertInstanceOf(AbstractEntity::class, $entity);
        self::assertInstanceOf(App::class, $entity);
        self::assertSame($values['id'], $entity->id);
        self::assertSame($values['ownerUuid'], $entity->ownerUuid);
        self::assertSame($values['spec'], $entity->spec);
        self::assertSame($values['defaultIngress'], $entity->defaultIngress);
        self::assertSame($values['createdAt'], $entity->createdAt);
        self::assertSame($values['updatedAt'], $entity->updatedAt);
        self::assertSame($values['activeDeployment'], $entity->activeDeployment);
        self::assertSame($values['inProgressDeployment'], $entity->inProgressDeployment);
        self::assertSame($values['lastDeploymentCreatedAt'], $entity->lastDeploymentCreatedAt);
        self::assertSame($values['liveUrl'], $entity->liveUrl);
        self::assertSame($values['region'], $entity->region);
        self::assertSame($values['tierSlug'], $entity->tierSlug);
        self::assertSame($values['liveUrlBase'], $entity->liveUrlBase);
        self::assertSame($values['liveDomain'], $entity->liveDomain);
        self::assertSame($values['domains'], $entity->domains);

        self::assertSame($values['ownerUuid'], $entity->owner_uuid);
        self::assertSame($values['defaultIngress'], $entity->default_ingress);
        self::assertSame($values['createdAt'], $entity->created_at);
        self::assertSame($values['updatedAt'], $entity->updated_at);
        self::assertSame($values['activeDeployment'], $entity->active_deployment);
        self::assertSame($values['inProgressDeployment'], $entity->in_progress_deployment);
        self::assertSame($values['lastDeploymentCreatedAt'], $entity->last_deployment_created_at);
        self::assertSame($values['liveUrl'], $entity->live_url);
        self::assertSame($values['tierSlug'], $entity->tier_slug);
        self::assertSame($values['liveUrlBase'], $entity->live_url_base);
        self::assertSame($values['liveDomain'], $entity->live_domain);
    }
}
