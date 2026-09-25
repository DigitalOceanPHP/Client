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
use DigitalOceanV2\Entity\FirewallRule;
use DigitalOceanV2\Entity\FirewallRuleInbound;
use DigitalOceanV2\Entity\FirewallRuleOutbound;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class FirewallRuleTest extends TestCase
{
    public function testInboundAllowRule(): void
    {
        $data = [
            'protocol' => 'tcp',
            'ports' => '80',
            'sources' => [
                'addresses' => ['0.0.0.0/0', '::/0'],
            ],
        ];

        $entity = new FirewallRuleInbound($data);

        self::assertInstanceOf(AbstractEntity::class, $entity);
        self::assertInstanceOf(FirewallRule::class, $entity);
        self::assertSame('tcp', $entity->protocol);
        self::assertSame('80', $entity->ports);
        self::assertFalse(isset($entity->action));

        $array = $entity->toArray();
        self::assertSame('tcp', $array['protocol']);
        self::assertSame('80', $array['ports']);
        self::assertArrayNotHasKey('action', $array);
        self::assertSame(['addresses' => ['0.0.0.0/0', '::/0']], $array['sources']);
    }

    public function testInboundDenyRuleAllProtocolWithZeroPort(): void
    {
        $data = [
            'protocol' => 'all',
            'ports' => '0',
            'sources' => [
                'addresses' => ['203.0.113.5'],
            ],
            'action' => 'deny',
        ];

        $entity = new FirewallRuleInbound($data);

        self::assertSame('all', $entity->protocol);
        self::assertSame('0', $entity->ports);
        self::assertSame('deny', $entity->action);

        $array = $entity->toArray();
        self::assertSame('all', $array['protocol']);
        self::assertSame('0', $array['ports']);
        self::assertSame('deny', $array['action']);
        self::assertSame(['addresses' => ['203.0.113.5']], $array['sources']);
    }

    public function testInboundDenyRuleAllProtocolWithOmittedPort(): void
    {
        $data = [
            'protocol' => 'all',
            'sources' => [
                'addresses' => ['203.0.113.5'],
            ],
            'action' => 'deny',
        ];

        $entity = new FirewallRuleInbound($data);

        self::assertSame('all', $entity->protocol);
        self::assertFalse(isset($entity->ports));
        self::assertSame('deny', $entity->action);

        $array = $entity->toArray();
        self::assertSame('all', $array['protocol']);
        self::assertArrayNotHasKey('ports', $array);
        self::assertSame('deny', $array['action']);
    }

    public function testInboundIcmpRuleOmitsPorts(): void
    {
        $data = [
            'protocol' => 'icmp',
            'sources' => [
                'addresses' => ['0.0.0.0/0'],
            ],
        ];

        $entity = new FirewallRuleInbound($data);

        $array = $entity->toArray();
        self::assertSame('icmp', $array['protocol']);
        self::assertArrayNotHasKey('ports', $array);
    }

    public function testOutboundDenyRule(): void
    {
        $data = [
            'protocol' => 'all',
            'ports' => '0',
            'destinations' => [
                'addresses' => ['198.51.100.0/24'],
            ],
            'action' => 'deny',
        ];

        $entity = new FirewallRuleOutbound($data);

        self::assertSame('all', $entity->protocol);
        self::assertSame('0', $entity->ports);
        self::assertSame('deny', $entity->action);

        $array = $entity->toArray();
        self::assertSame('all', $array['protocol']);
        self::assertSame('0', $array['ports']);
        self::assertSame('deny', $array['action']);
        self::assertSame(['addresses' => ['198.51.100.0/24']], $array['destinations']);
    }
}
