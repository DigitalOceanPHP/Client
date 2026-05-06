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
use DigitalOceanV2\Entity\ForwardingRule;
use DigitalOceanV2\Entity\HealthCheck;
use DigitalOceanV2\Entity\LoadBalancer;
use DigitalOceanV2\Entity\Region;
use DigitalOceanV2\Entity\StickySession;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class LoadBalancerTest extends TestCase
{
    public function testConstructor(): void
    {
        $values = [
            'id' => '4de7ac8b-495b-4884-9a69-1050c6793cd6',
            'name' => 'example-lb-01',
            'ip' => '104.131.186.241',
            'algorithm' => 'round_robin',
            'status' => 'new',
            'created_at' => '2017-02-01T22:22:58Z',
            'forwarding_rules' => [
                [
                    'entry_protocol' => 'http',
                    'entry_port' => 80,
                    'target_protocol' => 'http',
                    'target_port' => 80,
                    'certificate_id' => '',
                    'tls_passthrough' => false,
                ],
            ],
            'health_check' => [
                'protocol' => 'http',
                'port' => 80,
                'path' => '/',
                'check_interval_seconds' => 10,
                'response_timeout_seconds' => 5,
                'healthy_threshold' => 5,
                'unhealthy_threshold' => 3,
            ],
            'sticky_sessions' => [
                'type' => 'none',
            ],
            'region' => [
                'name' => 'New York 3',
                'slug' => 'nyc3',
                'available' => true,
                'features' => [
                    'private_networking',
                ],
                'sizes' => [
                    's-1vcpu-1gb',
                ],
            ],
            'droplet_ids' => [3164444, 3164445],
            'redirect_http_to_https' => false,
            'http_idle_timeout_seconds' => 60,
            'tag' => '',
        ];

        $entity = new LoadBalancer($values);

        self::assertInstanceOf(AbstractEntity::class, $entity);
        self::assertInstanceOf(LoadBalancer::class, $entity);
        self::assertSame($values['id'], $entity->id);
        self::assertSame($values['name'], $entity->name);
        self::assertSame($values['ip'], $entity->ip);
        self::assertSame($values['algorithm'], $entity->algorithm);
        self::assertSame($values['status'], $entity->status);
        self::assertSame($values['created_at'], $entity->createdAt);
        self::assertInstanceOf(ForwardingRule::class, $entity->forwardingRules[0]);
        self::assertInstanceOf(HealthCheck::class, $entity->healthCheck);
        self::assertInstanceOf(StickySession::class, $entity->stickySessions);
        self::assertInstanceOf(Region::class, $entity->region);
        self::assertSame($values['droplet_ids'], $entity->dropletIds);
        self::assertFalse($entity->redirectHttpToHttps);
        self::assertSame(60, $entity->httpIdleTimeoutSeconds);
        self::assertSame('', $entity->tag);
    }

    public function testConstructorAcceptsApiShapedObjects(): void
    {
        $payload = \json_decode(<<<'JSON'
{
  "id": "4de7ac8b-495b-4884-9a69-1050c6793cd6",
  "name": "example-lb-01",
  "ip": "104.131.186.241",
  "ipv6": "2604:a880:800:14::85f5:c000",
  "algorithm": "round_robin",
  "status": "new",
  "created_at": "2017-02-01T22:22:58Z",
  "project_id": "9cc10173-e9ea-4176-9dbc-a4cee4c4ff30",
  "size": "lb-small",
  "size_unit": 3,
  "enable_proxy_protocol": true,
  "enable_backend_keepalive": true,
  "vpc_uuid": "c33931f2-a26a-4e61-b85c-4e95a2ec431b",
  "disable_lets_encrypt_dns_records": false,
  "network": "EXTERNAL",
  "network_stack": "DUALSTACK",
  "type": "REGIONAL",
  "tls_cipher_policy": "STRONG",
  "firewall": {
    "allow": ["ip:1.2.3.4"],
    "deny": ["cidr:10.0.0.0/8"]
  },
  "forwarding_rules": [
    {
      "entry_protocol": "http",
      "entry_port": 80,
      "target_protocol": "http",
      "target_port": 80,
      "certificate_id": "",
      "tls_passthrough": false
    }
  ],
  "health_check": {
    "protocol": "http",
    "port": 80,
    "path": "/",
    "check_interval_seconds": 10,
    "response_timeout_seconds": 5,
    "healthy_threshold": 5,
    "unhealthy_threshold": 3
  },
  "sticky_sessions": {
    "type": "cookies",
    "cookie_name": "DO-LB",
    "cookie_ttl_seconds": 300
  },
  "region": {
    "name": "New York 3",
    "slug": "nyc3",
    "available": true,
    "features": ["private_networking"],
    "sizes": ["s-1vcpu-1gb"]
  },
  "droplet_ids": [3164444],
  "redirect_http_to_https": true,
  "http_idle_timeout_seconds": 90
}
JSON, false, 512, \JSON_THROW_ON_ERROR);

        $entity = new LoadBalancer($payload);

        self::assertSame('example-lb-01', $entity->name);
        self::assertSame('2604:a880:800:14::85f5:c000', $entity->ipv6);
        self::assertSame('9cc10173-e9ea-4176-9dbc-a4cee4c4ff30', $entity->projectId);
        self::assertSame('lb-small', $entity->size);
        self::assertSame(3, $entity->sizeUnit);
        self::assertTrue($entity->enableProxyProtocol);
        self::assertTrue($entity->enableBackendKeepalive);
        self::assertSame('c33931f2-a26a-4e61-b85c-4e95a2ec431b', $entity->vpcUuid);
        self::assertFalse($entity->disableLetsEncryptDnsRecords);
        self::assertSame('EXTERNAL', $entity->network);
        self::assertSame('DUALSTACK', $entity->networkStack);
        self::assertSame('REGIONAL', $entity->type);
        self::assertSame('STRONG', $entity->tlsCipherPolicy);
        self::assertSame(['allow' => ['ip:1.2.3.4'], 'deny' => ['cidr:10.0.0.0/8']], $entity->firewall);
        self::assertSame(300, $entity->stickySessions->cookieTtlSeconds);
    }

    public function testToArrayPreservesDocumentedFieldsNeededForUpdate(): void
    {
        $entity = new LoadBalancer([
            'name' => 'global-lb-01',
            'type' => 'GLOBAL',
            'network' => 'EXTERNAL',
            'network_stack' => 'DUALSTACK',
            'project_id' => '9cc10173-e9ea-4176-9dbc-a4cee4c4ff30',
            'size_unit' => 3,
            'algorithm' => 'round_robin',
            'forwarding_rules' => [
                [
                    'entry_protocol' => 'http',
                    'entry_port' => 80,
                    'target_protocol' => 'http',
                    'target_port' => 80,
                    'certificate_id' => '',
                    'tls_passthrough' => false,
                ],
            ],
            'health_check' => [
                'protocol' => 'http',
                'port' => 80,
                'path' => '/',
                'check_interval_seconds' => 10,
                'response_timeout_seconds' => 5,
                'healthy_threshold' => 5,
                'unhealthy_threshold' => 3,
            ],
            'sticky_sessions' => [
                'type' => 'none',
            ],
            'redirect_http_to_https' => false,
            'http_idle_timeout_seconds' => 60,
            'enable_backend_keepalive' => true,
            'enable_proxy_protocol' => true,
            'disable_lets_encrypt_dns_records' => false,
            'firewall' => [
                'allow' => ['ip:1.2.3.4'],
                'deny' => ['cidr:10.0.0.0/8'],
            ],
            'domains' => [
                [
                    'name' => 'example.com',
                    'is_managed' => true,
                ],
            ],
            'glb_settings' => [
                'target_protocol' => 'http',
                'target_port' => 80,
            ],
            'target_load_balancer_ids' => [
                '7dbf91fe-cbdb-48dc-8290-c3a181554905',
            ],
            'tls_cipher_policy' => 'STRONG',
            'vpc_uuid' => 'c33931f2-a26a-4e61-b85c-4e95a2ec431b',
        ]);

        self::assertSame([
            'name' => 'global-lb-01',
            'algorithm' => 'round_robin',
            'size_unit' => 3,
            'forwarding_rules' => [
                [
                    'entry_protocol' => 'http',
                    'entry_port' => 80,
                    'target_protocol' => 'http',
                    'target_port' => 80,
                    'certificate_id' => '',
                    'tls_passthrough' => false,
                ],
            ],
            'health_check' => [
                'protocol' => 'http',
                'port' => 80,
                'path' => '/',
                'check_interval_seconds' => 10,
                'response_timeout_seconds' => 5,
                'healthy_threshold' => 5,
                'unhealthy_threshold' => 3,
            ],
            'sticky_sessions' => [
                'type' => 'none',
            ],
            'redirect_http_to_https' => false,
            'enable_proxy_protocol' => true,
            'enable_backend_keepalive' => true,
            'http_idle_timeout_seconds' => 60,
            'vpc_uuid' => 'c33931f2-a26a-4e61-b85c-4e95a2ec431b',
            'disable_lets_encrypt_dns_records' => false,
            'project_id' => '9cc10173-e9ea-4176-9dbc-a4cee4c4ff30',
            'firewall' => [
                'allow' => ['ip:1.2.3.4'],
                'deny' => ['cidr:10.0.0.0/8'],
            ],
            'network' => 'EXTERNAL',
            'network_stack' => 'DUALSTACK',
            'type' => 'GLOBAL',
            'domains' => [
                [
                    'name' => 'example.com',
                    'is_managed' => true,
                ],
            ],
            'glb_settings' => [
                'target_protocol' => 'http',
                'target_port' => 80,
            ],
            'target_load_balancer_ids' => [
                '7dbf91fe-cbdb-48dc-8290-c3a181554905',
            ],
            'tls_cipher_policy' => 'STRONG',
        ], $entity->toArray());
    }

    public function testToArrayPrefersTagOverDropletIds(): void
    {
        $entity = new LoadBalancer([
            'name' => 'tag-lb-01',
            'region' => [
                'name' => 'New York 3',
                'slug' => 'nyc3',
                'available' => true,
                'features' => [],
                'sizes' => [],
            ],
            'algorithm' => 'round_robin',
            'forwarding_rules' => [
                [
                    'entry_protocol' => 'http',
                    'entry_port' => 80,
                    'target_protocol' => 'http',
                    'target_port' => 80,
                ],
            ],
            'health_check' => [
                'protocol' => 'http',
                'port' => 80,
                'path' => '/',
                'check_interval_seconds' => 10,
                'response_timeout_seconds' => 5,
                'healthy_threshold' => 5,
                'unhealthy_threshold' => 3,
            ],
            'sticky_sessions' => [
                'type' => 'none',
            ],
            'tag' => 'prod:web',
            'droplet_ids' => [3164444],
            'redirect_http_to_https' => false,
            'http_idle_timeout_seconds' => 60,
        ]);

        $data = $entity->toArray();

        self::assertSame('prod:web', $data['tag']);
        self::assertArrayNotHasKey('droplet_ids', $data);
    }

    public function testToArrayRetainsExistingRegionalBehaviorWhenOptionalFieldsAreUnset(): void
    {
        $entity = new LoadBalancer([
            'name' => 'example-lb-01',
            'region' => [
                'name' => 'New York 3',
                'slug' => 'nyc3',
                'available' => true,
                'features' => [],
                'sizes' => [],
            ],
            'algorithm' => 'round_robin',
            'forwarding_rules' => [
                [
                    'entry_protocol' => 'http',
                    'entry_port' => 80,
                    'target_protocol' => 'http',
                    'target_port' => 80,
                    'certificate_id' => '',
                    'tls_passthrough' => false,
                ],
            ],
            'health_check' => [
                'protocol' => 'http',
                'port' => 80,
                'path' => '/',
                'check_interval_seconds' => 10,
                'response_timeout_seconds' => 5,
                'healthy_threshold' => 5,
                'unhealthy_threshold' => 3,
            ],
            'sticky_sessions' => [
                'type' => 'none',
            ],
            'droplet_ids' => [3164444, 3164445],
            'redirect_http_to_https' => false,
            'http_idle_timeout_seconds' => 60,
        ]);

        self::assertSame([
            'name' => 'example-lb-01',
            'region' => 'nyc3',
            'algorithm' => 'round_robin',
            'forwarding_rules' => [
                [
                    'entry_protocol' => 'http',
                    'entry_port' => 80,
                    'target_protocol' => 'http',
                    'target_port' => 80,
                    'certificate_id' => '',
                    'tls_passthrough' => false,
                ],
            ],
            'health_check' => [
                'protocol' => 'http',
                'port' => 80,
                'path' => '/',
                'check_interval_seconds' => 10,
                'response_timeout_seconds' => 5,
                'healthy_threshold' => 5,
                'unhealthy_threshold' => 3,
            ],
            'sticky_sessions' => [
                'type' => 'none',
            ],
            'droplet_ids' => [3164444, 3164445],
            'redirect_http_to_https' => false,
            'http_idle_timeout_seconds' => 60,
        ], $entity->toArray());
    }
}
