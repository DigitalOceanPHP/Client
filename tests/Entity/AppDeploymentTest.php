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
            'id' => 'b6bdf840-2854-4f87-a36c-5f231c617c84',
            'spec' => [],
            'services' => [],
            'staticSites' => [],
            'workers' => [],
            'jobs' => [],
            'phaseLastUpdatedAt' => '2021-02-10T17:05:30Z',
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
        self::assertSame($values['phaseLastUpdatedAt'], $entity->phaseLastUpdatedAt);
        self::assertSame($values['createdAt'], $entity->createdAt);
        self::assertSame($values['updatedAt'], $entity->updatedAt);
        self::assertSame($values['cause'], $entity->cause);
        self::assertSame($values['clonedFrom'], $entity->clonedFrom);
        self::assertSame($values['progress'], $entity->progress);
        self::assertSame($values['phase'], $entity->phase);
        self::assertSame($values['tierSlug'], $entity->tierSlug);

        self::assertSame($values['staticSites'], $entity->static_sites);
        self::assertSame($values['phaseLastUpdatedAt'], $entity->phase_last_updated_at);
        self::assertSame($values['createdAt'], $entity->created_at);
        self::assertSame($values['updatedAt'], $entity->updated_at);
        self::assertSame($values['clonedFrom'], $entity->cloned_from);
        self::assertSame($values['tierSlug'], $entity->tier_slug);
    }

    public function testConstructorAcceptsApiShapedObjects(): void
    {
        $payload = \json_decode(<<<'JSON'
{
  "id": "b6bdf840-2854-4f87-a36c-5f231c617c84",
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
  "services": [
    {
      "name": "web",
      "source_commit_hash": "abc123",
      "alerts": [
        {
          "rule": "CPU_UTILIZATION"
        }
      ]
    }
  ],
  "static_sites": [
    {
      "name": "docs",
      "routes": [
        {
          "path": "/docs"
        }
      ]
    }
  ],
  "workers": [
    {
      "name": "queue",
      "instance_count": 1
    }
  ],
  "jobs": [
    {
      "name": "migrate",
      "kind": "POST_DEPLOY"
    }
  ],
  "phase_last_updated_at": "2024-01-02T00:03:00Z",
  "created_at": "2024-01-02T00:00:00Z",
  "updated_at": "2024-01-02T00:04:00Z",
  "cause": "manual",
  "cloned_from": "dep-0",
  "progress": {
    "success_steps": 1,
    "steps": [
      {
        "name": "build",
        "components": [
          {
            "name": "web"
          }
        ]
      }
    ]
  },
  "phase": "ACTIVE",
  "tier_slug": "basic"
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
        $expectedServices = [
            [
                'name' => 'web',
                'source_commit_hash' => 'abc123',
                'alerts' => [
                    [
                        'rule' => 'CPU_UTILIZATION',
                    ],
                ],
            ],
        ];
        $expectedStaticSites = [
            [
                'name' => 'docs',
                'routes' => [
                    [
                        'path' => '/docs',
                    ],
                ],
            ],
        ];
        $expectedWorkers = [
            [
                'name' => 'queue',
                'instance_count' => 1,
            ],
        ];
        $expectedJobs = [
            [
                'name' => 'migrate',
                'kind' => 'POST_DEPLOY',
            ],
        ];
        $expectedProgress = [
            'success_steps' => 1,
            'steps' => [
                [
                    'name' => 'build',
                    'components' => [
                        [
                            'name' => 'web',
                        ],
                    ],
                ],
            ],
        ];

        $entity = new AppDeployment($payload);

        self::assertInstanceOf(AbstractEntity::class, $entity);
        self::assertInstanceOf(AppDeployment::class, $entity);
        self::assertSame('b6bdf840-2854-4f87-a36c-5f231c617c84', $entity->id);
        self::assertSame($expectedSpec, $entity->spec);
        self::assertSame($expectedServices, $entity->services);
        self::assertSame($expectedStaticSites, $entity->staticSites);
        self::assertSame($expectedWorkers, $entity->workers);
        self::assertSame($expectedJobs, $entity->jobs);
        self::assertSame($expectedProgress, $entity->progress);
        self::assertSame('2024-01-02T00:03:00Z', $entity->phaseLastUpdatedAt);
        self::assertSame('dep-0', $entity->clonedFrom);
        self::assertSame('basic', $entity->tierSlug);
        self::assertSame($expectedStaticSites, $entity->static_sites);
        self::assertSame('2024-01-02T00:03:00Z', $entity->phase_last_updated_at);
        self::assertSame('dep-0', $entity->cloned_from);
        self::assertSame('basic', $entity->tier_slug);
    }
}
