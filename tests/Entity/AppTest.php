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
            'id' => '4f6c71e2-1e90-4762-9fee-6cc4a0a9f2cf',
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

    public function testConstructorAcceptsApiShapedObjects(): void
    {
        $payload = \json_decode(<<<'JSON'
{
  "id": "4f6c71e2-1e90-4762-9fee-6cc4a0a9f2cf",
  "owner_uuid": "uuid",
  "spec": {
    "name": "sample-golang",
    "services": [
      {
        "name": "web",
        "github": {
          "repo": "digitalocean/sample-golang",
          "branch": "main"
        },
        "routes": [
          {
            "path": "/"
          }
        ]
      }
    ]
  },
  "default_ingress": "sample.ondigitalocean.app",
  "created_at": "2024-01-01T00:00:00Z",
  "updated_at": "2024-01-02T00:00:00Z",
  "active_deployment": {
    "id": "dep-active",
    "phase": "ACTIVE",
    "progress": {
      "success_steps": 1,
      "steps": [
        {
          "name": "build",
          "status": "SUCCESS"
        }
      ]
    }
  },
  "in_progress_deployment": {
    "id": "dep-progress",
    "phase": "BUILDING",
    "spec": {
      "name": "sample-golang"
    }
  },
  "last_deployment_created_at": "2024-01-02T00:01:00Z",
  "live_url": "https://sample.ondigitalocean.app",
  "region": {
    "slug": "nyc",
    "label": "New York"
  },
  "tier_slug": "basic",
  "live_url_base": "https://sample.ondigitalocean.app",
  "live_domain": "sample.ondigitalocean.app",
  "domains": [
    {
      "domain": "example.com",
      "type": "PRIMARY"
    }
  ]
}
JSON, false, 512, \JSON_THROW_ON_ERROR);

        $expectedSpec = [
            'name' => 'sample-golang',
            'services' => [
                [
                    'name' => 'web',
                    'github' => [
                        'repo' => 'digitalocean/sample-golang',
                        'branch' => 'main',
                    ],
                    'routes' => [
                        [
                            'path' => '/',
                        ],
                    ],
                ],
            ],
        ];
        $expectedActiveDeployment = [
            'id' => 'dep-active',
            'phase' => 'ACTIVE',
            'progress' => [
                'success_steps' => 1,
                'steps' => [
                    [
                        'name' => 'build',
                        'status' => 'SUCCESS',
                    ],
                ],
            ],
        ];
        $expectedInProgressDeployment = [
            'id' => 'dep-progress',
            'phase' => 'BUILDING',
            'spec' => [
                'name' => 'sample-golang',
            ],
        ];
        $expectedRegion = [
            'slug' => 'nyc',
            'label' => 'New York',
        ];
        $expectedDomains = [
            [
                'domain' => 'example.com',
                'type' => 'PRIMARY',
            ],
        ];

        $entity = new App($payload);

        self::assertInstanceOf(AbstractEntity::class, $entity);
        self::assertInstanceOf(App::class, $entity);
        self::assertSame('4f6c71e2-1e90-4762-9fee-6cc4a0a9f2cf', $entity->id);
        self::assertSame('uuid', $entity->ownerUuid);
        self::assertSame($expectedSpec, $entity->spec);
        self::assertSame($expectedActiveDeployment, $entity->activeDeployment);
        self::assertSame($expectedInProgressDeployment, $entity->inProgressDeployment);
        self::assertSame($expectedRegion, $entity->region);
        self::assertSame($expectedDomains, $entity->domains);
        self::assertSame($expectedActiveDeployment, $entity->active_deployment);
        self::assertSame($expectedInProgressDeployment, $entity->in_progress_deployment);
        self::assertSame('2024-01-02T00:01:00Z', $entity->last_deployment_created_at);
        self::assertSame('basic', $entity->tier_slug);
    }
}
