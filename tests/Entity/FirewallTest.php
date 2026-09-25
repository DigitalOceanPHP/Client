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
use DigitalOceanV2\Entity\Firewall as FirewallEntity;
use DigitalOceanV2\Entity\FirewallRuleInbound;
use DigitalOceanV2\Entity\FirewallRuleOutbound;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class FirewallTest extends TestCase
{
    public function testConstructorAndToArray(): void
    {
        $values = [
            'id' => 'bb4b2611-3d72-467b-8602-280330ecd65c',
            'name' => 'web-firewall',
            'status' => 'succeeded',
            'created_at' => '2026-09-09T00:00:00Z',
            'pending_changes' => [],
            'inbound_rules' => [
                [
                    'protocol' => 'tcp',
                    'ports' => '80',
                    'sources' => ['addresses' => ['0.0.0.0/0']],
                ],
                [
                    'protocol' => 'all',
                    'ports' => '0',
                    'sources' => ['addresses' => ['203.0.113.5']],
                    'action' => 'deny',
                ],
            ],
            'outbound_rules' => [
                [
                    'protocol' => 'tcp',
                    'ports' => '443',
                    'destinations' => ['addresses' => ['0.0.0.0/0']],
                ],
                [
                    'protocol' => 'all',
                    'ports' => '0',
                    'destinations' => ['addresses' => ['198.51.100.0/24']],
                    'action' => 'deny',
                ],
            ],
            'droplet_ids' => [12345],
            'tags' => ['frontend'],
        ];

        $entity = new FirewallEntity($values);

        self::assertInstanceOf(AbstractEntity::class, $entity);
        self::assertInstanceOf(FirewallEntity::class, $entity);
        self::assertSame('bb4b2611-3d72-467b-8602-280330ecd65c', $entity->id);
        self::assertSame('web-firewall', $entity->name);
        self::assertSame('succeeded', $entity->status);
        self::assertCount(2, $entity->inboundRules);
        self::assertCount(2, $entity->outboundRules);
        self::assertInstanceOf(FirewallRuleInbound::class, $entity->inboundRules[0]);
        self::assertInstanceOf(FirewallRuleInbound::class, $entity->inboundRules[1]);
        self::assertInstanceOf(FirewallRuleOutbound::class, $entity->outboundRules[0]);
        self::assertInstanceOf(FirewallRuleOutbound::class, $entity->outboundRules[1]);
        self::assertFalse(isset($entity->inboundRules[0]->action));
        self::assertSame('deny', $entity->inboundRules[1]->action);
        self::assertFalse(isset($entity->outboundRules[0]->action));
        self::assertSame('deny', $entity->outboundRules[1]->action);

        $array = $entity->toArray();
        self::assertSame('web-firewall', $array['name']);
        self::assertSame([12345], $array['droplet_ids']);
        self::assertSame(['frontend'], $array['tags']);
        self::assertCount(2, $array['inbound_rules']);
        self::assertCount(2, $array['outbound_rules']);
        self::assertSame('tcp', $array['inbound_rules'][0]['protocol']);
        self::assertSame('80', $array['inbound_rules'][0]['ports']);
        self::assertArrayNotHasKey('action', $array['inbound_rules'][0]);
        self::assertSame('all', $array['inbound_rules'][1]['protocol']);
        self::assertSame('0', $array['inbound_rules'][1]['ports']);
        self::assertSame('deny', $array['inbound_rules'][1]['action']);
        self::assertSame('tcp', $array['outbound_rules'][0]['protocol']);
        self::assertSame('443', $array['outbound_rules'][0]['ports']);
        self::assertArrayNotHasKey('action', $array['outbound_rules'][0]);
        self::assertSame('all', $array['outbound_rules'][1]['protocol']);
        self::assertSame('0', $array['outbound_rules'][1]['ports']);
        self::assertSame('deny', $array['outbound_rules'][1]['action']);
    }
}
