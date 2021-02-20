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

namespace DigitalOceanV2\Tests;

use DigitalOceanV2\Api\Account;
use DigitalOceanV2\Api\Action;
use DigitalOceanV2\Api\Certificate;
use DigitalOceanV2\Api\Database;
use DigitalOceanV2\Api\Domain;
use DigitalOceanV2\Api\DomainRecord;
use DigitalOceanV2\Api\Droplet;
use DigitalOceanV2\Api\Firewall;
use DigitalOceanV2\Api\FloatingIp;
use DigitalOceanV2\Api\Image;
use DigitalOceanV2\Api\Key;
use DigitalOceanV2\Api\LoadBalancer;
use DigitalOceanV2\Api\Region;
use DigitalOceanV2\Api\Size;
use DigitalOceanV2\Api\Snapshot;
use DigitalOceanV2\Api\Tag;
use DigitalOceanV2\Api\Volume;
use DigitalOceanV2\Client;
use Http\Client\Common\HttpMethodsClientInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <graham@alt-three.com>
 */
final class ClientTest extends TestCase
{
    public function testCreateClient(): void
    {
        $client = new Client();

        self::assertInstanceOf(Client::class, $client);
        self::assertInstanceOf(HttpMethodsClientInterface::class, $client->getHttpClient());
    }

    public function testCreateApis(): void
    {
        $client = new Client();

        self::assertInstanceOf(Account::class, $client->account());
        self::assertInstanceOf(Action::class, $client->action());
        self::assertInstanceOf(Certificate::class, $client->certificate());
        self::assertInstanceOf(Database::class, $client->database());
        self::assertInstanceOf(Domain::class, $client->domain());
        self::assertInstanceOf(DomainRecord::class, $client->domainRecord());
        self::assertInstanceOf(Droplet::class, $client->droplet());
        self::assertInstanceOf(Firewall::class, $client->firewall());
        self::assertInstanceOf(FloatingIp::class, $client->floatingIp());
        self::assertInstanceOf(Image::class, $client->image());
        self::assertInstanceOf(Key::class, $client->key());
        self::assertInstanceOf(LoadBalancer::class, $client->loadBalancer());
        self::assertInstanceOf(Region::class, $client->region());
        self::assertInstanceOf(Size::class, $client->size());
        self::assertInstanceOf(Snapshot::class, $client->snapshot());
        self::assertInstanceOf(Tag::class, $client->tag());
        self::assertInstanceOf(Volume::class, $client->volume());
    }
}
